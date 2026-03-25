<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;
use App\Models\KategoriArtikelModel;

class ArtikelController extends BaseController
{
    private ArtikelModel $model;
    private KategoriArtikelModel $kategoriModel;

    public function __construct()
    {
        $this->model        = new ArtikelModel();
        $this->kategoriModel = new KategoriArtikelModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status') ?? '';
        $search = $this->request->getGet('q') ?? '';

        $builder = $this->model
            ->select('artikel.*, kategori_artikel.nama as nama_kategori')
            ->join('kategori_artikel', 'kategori_artikel.id = artikel.kategori_id', 'left')
            ->orderBy('artikel.created_at', 'DESC');

        // Kontributor can only see their own articles
        if (session('admin_role') === 'kontributor') {
            $builder->where('artikel.user_id', session('admin_id'));
        }

        if ($status !== '') {
            $builder->where('artikel.status', $status);
        }
        if ($search !== '') {
            $builder->groupStart()
                ->like('artikel.judul', $search)
                ->orLike('artikel.ringkasan', $search)
                ->groupEnd();
        }

        return view('admin/artikel/index', [
            'title'       => 'Manajemen Artikel',
            'artikel'     => $builder->paginate(15, 'artikel'),
            'pager'       => $this->model->pager,
            'kategori'    => $this->kategoriModel->orderBy('urutan', 'ASC')->findAll(),
            'status_filter' => $status,
            'search'      => $search,
            'total_all'       => $this->model->countAllResults(false),
            'total_published' => $this->model->where('status', 'published')->countAllResults(false),
            'total_draft'     => $this->model->where('status', 'draft')->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/artikel/create', [
            'title'    => 'Tulis Artikel Baru',
            'kategori' => $this->kategoriModel->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'judul'      => 'required|max_length[255]',
            'kategori_id'=> 'required|integer',
            'konten'     => 'required',
            'status'     => 'required|in_list[draft,published,archived]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $judul  = $this->request->getPost('judul');
        $slug   = $this->_uniqueSlug(slug_generate($judul));

        $thumbnail = null;
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $thumbnail = $uploader->upload('thumbnail', 'artikel');
            if (! $thumbnail) {
                return redirect()->back()->withInput()->with('error', 'Gagal upload thumbnail.');
            }
        }

        $status = $this->request->getPost('status');
        $publishedAt = null;
        if ($status === 'published') {
            $publishedAt = $this->request->getPost('published_at') ?: date('Y-m-d H:i:s');
        }

        $this->model->insert([
            'kategori_id' => $this->request->getPost('kategori_id'),
            'user_id'     => session()->get('admin_id'),
            'judul'       => $judul,
            'slug'        => $slug,
            'ringkasan'   => $this->request->getPost('ringkasan'),
            'konten'      => $this->request->getPost('konten'),
            'thumbnail'   => $thumbnail,
            'status'      => $status,
            'is_featured' => (int) ($this->request->getPost('is_featured') === '1'),
            'tags'        => $this->request->getPost('tags'),
            'published_at'=> $publishedAt,
        ]);

        return redirect()->to(base_url('admin/artikel'))->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $artikel = $this->model->find($id);
        if (! $artikel) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Artikel tidak ditemukan.');
        }

        // Kontributor can only edit their own articles
        if (session('admin_role') === 'kontributor' && (int)$artikel['user_id'] !== (int)session('admin_id')) {
            return redirect()->to(base_url('admin/artikel'))->with('error', 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }

        return view('admin/artikel/edit', [
            'title'    => 'Edit Artikel',
            'artikel'  => $artikel,
            'kategori' => $this->kategoriModel->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $artikel = $this->model->find($id);
        if (! $artikel) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Artikel tidak ditemukan.');
        }

        // Kontributor can only update their own articles
        if (session('admin_role') === 'kontributor' && (int)$artikel['user_id'] !== (int)session('admin_id')) {
            return redirect()->to(base_url('admin/artikel'))->with('error', 'Anda tidak memiliki izin untuk mengubah artikel ini.');
        }

        $rules = [
            'judul'       => 'required|max_length[255]',
            'kategori_id' => 'required|integer',
            'konten'      => 'required',
            'status'      => 'required|in_list[draft,published,archived]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $judul = $this->request->getPost('judul');
        // Re-generate slug only if judul changed
        $slug = $artikel['slug'];
        if ($judul !== $artikel['judul']) {
            $slug = $this->_uniqueSlug(slug_generate($judul), $id);
        }

        $thumbnail = $artikel['thumbnail'];
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $newThumb = $uploader->upload('thumbnail', 'artikel');
            if ($newThumb) {
                // Hapus thumbnail lama
                if ($thumbnail) {
                    $uploader->delete('artikel', $thumbnail);
                }
                $thumbnail = $newThumb;
            }
        }

        $status = $this->request->getPost('status');
        $publishedAt = $artikel['published_at'];
        if ($status === 'published' && ! $publishedAt) {
            $publishedAt = $this->request->getPost('published_at') ?: date('Y-m-d H:i:s');
        }

        $this->model->update($id, [
            'kategori_id' => $this->request->getPost('kategori_id'),
            'judul'       => $judul,
            'slug'        => $slug,
            'ringkasan'   => $this->request->getPost('ringkasan'),
            'konten'      => $this->request->getPost('konten'),
            'thumbnail'   => $thumbnail,
            'status'      => $status,
            'is_featured' => (int) ($this->request->getPost('is_featured') === '1'),
            'tags'        => $this->request->getPost('tags'),
            'published_at'=> $publishedAt,
        ]);

        return redirect()->to(base_url('admin/artikel'))->with('success', 'Artikel berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $artikel = $this->model->find($id);
        if (! $artikel) {
            return redirect()->back()->with('error', 'Artikel tidak ditemukan.');
        }

        // Kontributor can only delete their own articles
        if (session('admin_role') === 'kontributor' && (int)$artikel['user_id'] !== (int)session('admin_id')) {
            return redirect()->to(base_url('admin/artikel'))->with('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
        }

        if (! empty($artikel['thumbnail'])) {
            (new \App\Libraries\ImageUpload())->delete('artikel', $artikel['thumbnail']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/artikel'))->with('success', 'Artikel berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $artikel = $this->model->find($id);
        if (! $artikel) {
            return redirect()->back()->with('error', 'Artikel tidak ditemukan.');
        }

        if ($artikel['status'] === 'published') {
            $this->model->update($id, ['status' => 'draft']);
            $msg = 'Artikel dikembalikan ke draft.';
        } else {
            $this->model->update($id, [
                'status'       => 'published',
                'published_at' => $artikel['published_at'] ?: date('Y-m-d H:i:s'),
            ]);
            $msg = 'Artikel dipublikasikan.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function toggleFeatured(int $id)
    {
        $artikel = $this->model->find($id);
        if (! $artikel) {
            return redirect()->back()->with('error', 'Artikel tidak ditemukan.');
        }

        $newVal = $artikel['is_featured'] ? 0 : 1;
        $this->model->update($id, ['is_featured' => $newVal]);
        $msg = $newVal ? 'Artikel ditandai sebagai pilihan.' : 'Artikel dihapus dari pilihan.';

        return redirect()->back()->with('success', $msg);
    }

    // ----------------------------------------------------------------
    // Helper
    // ----------------------------------------------------------------

    private function _uniqueSlug(string $slug, int $excludeId = 0): string
    {
        $base  = $slug;
        $i     = 1;
        while (true) {
            $q = $this->model->where('slug', $slug);
            if ($excludeId) {
                $q = $q->where('id !=', $excludeId);
            }
            if (! $q->first()) {
                break;
            }
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
