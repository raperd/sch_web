<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;

class GaleriController extends BaseController
{
    private GaleriModel $model;
    private KategoriGaleriModel $kategoriModel;

    public function __construct()
    {
        $this->model        = new GaleriModel();
        $this->kategoriModel = new KategoriGaleriModel();
    }

    public function index(): string
    {
        $kategoriId = $this->request->getGet('kategori') ?? '';
        $search     = $this->request->getGet('q') ?? '';

        $builder = $this->model
            ->select('galeri.*, kategori_galeri.nama as kategori_nama')
            ->join('kategori_galeri', 'kategori_galeri.id = galeri.kategori_id', 'left')
            ->orderBy('galeri.urutan', 'ASC')
            ->orderBy('galeri.created_at', 'DESC');

        if ($kategoriId !== '') {
            $builder->where('galeri.kategori_id', $kategoriId);
        }
        if ($search !== '') {
            $builder->like('galeri.judul', $search);
        }

        return view('admin/galeri/index', [
            'title'          => 'Manajemen Galeri',
            'galeri'         => $builder->paginate(20, 'galeri'),
            'pager'          => $this->model->pager,
            'kategori'       => $this->kategoriModel->orderBy('urutan', 'ASC')->findAll(),
            'kategori_filter'=> $kategoriId,
            'search'         => $search,
            'total_all'      => $this->model->countAllResults(false),
            'total_featured' => $this->model->where('is_featured', 1)->countAllResults(false),
        ]);
    }

    public function upload(): string
    {
        return view('admin/galeri/upload', [
            'title'       => 'Upload Foto / Video',
            'kategori'    => $this->kategoriModel->orderBy('urutan', 'ASC')->findAll(),
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = [
            'judul'       => 'required|max_length[255]',
            'kategori_id' => 'required|integer',
            'tipe'        => 'required|in_list[foto,video]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploader  = new \App\Libraries\ImageUpload();
        $file_path = null;
        $thumbnail = null;

        $mainFile = $this->request->getFile('file_path');
        if ($mainFile && $mainFile->isValid() && ! $mainFile->hasMoved()) {
            $file_path = $uploader->upload('file_path', 'galeri');
            if (! $file_path) {
                return redirect()->back()->withInput()->with('error', 'Gagal upload file utama.');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'File foto/video wajib diupload.');
        }

        $thumbFile = $this->request->getFile('thumbnail');
        if ($thumbFile && $thumbFile->isValid() && ! $thumbFile->hasMoved()) {
            $thumbnail = $uploader->upload('thumbnail', 'galeri');
        }

        $this->model->insert([
            'kategori_id' => $this->request->getPost('kategori_id'),
            'judul'       => $this->request->getPost('judul'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'file_path'   => $file_path,
            'tipe'        => $this->request->getPost('tipe'),
            'thumbnail'   => $thumbnail,
            'is_featured' => (int) ($this->request->getPost('is_featured') === '1'),
            'urutan'      => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/galeri'))->with('success', 'Foto berhasil diupload.');
    }

    public function edit(int $id): string
    {
        $galeri = $this->model->find($id);
        if (! $galeri) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item galeri tidak ditemukan.');
        }

        return view('admin/galeri/edit', [
            'title'    => 'Edit Galeri',
            'galeri'   => $galeri,
            'kategori' => $this->kategoriModel->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $galeri = $this->model->find($id);
        if (! $galeri) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item galeri tidak ditemukan.');
        }

        $rules = [
            'judul'       => 'required|max_length[255]',
            'kategori_id' => 'required|integer',
            'tipe'        => 'required|in_list[foto,video]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploader  = new \App\Libraries\ImageUpload();
        $file_path = $galeri['file_path'];
        $thumbnail = $galeri['thumbnail'];

        $mainFile = $this->request->getFile('file_path');
        if ($mainFile && $mainFile->isValid() && ! $mainFile->hasMoved()) {
            $newFile = $uploader->upload('file_path', 'galeri');
            if ($newFile) {
                if ($file_path) {
                    $uploader->delete('galeri', $file_path);
                }
                $file_path = $newFile;
            }
        }

        $thumbFile = $this->request->getFile('thumbnail');
        if ($thumbFile && $thumbFile->isValid() && ! $thumbFile->hasMoved()) {
            $newThumb = $uploader->upload('thumbnail', 'galeri');
            if ($newThumb) {
                if ($thumbnail) {
                    $uploader->delete('galeri', $thumbnail);
                }
                $thumbnail = $newThumb;
            }
        }

        $this->model->update($id, [
            'kategori_id' => $this->request->getPost('kategori_id'),
            'judul'       => $this->request->getPost('judul'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'file_path'   => $file_path,
            'tipe'        => $this->request->getPost('tipe'),
            'thumbnail'   => $thumbnail,
            'is_featured' => (int) ($this->request->getPost('is_featured') === '1'),
            'urutan'      => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/galeri'))->with('success', 'Galeri berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $galeri = $this->model->find($id);
        if (! $galeri) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        $uploader = new \App\Libraries\ImageUpload();
        if (! empty($galeri['file_path'])) {
            $uploader->delete('galeri', $galeri['file_path']);
        }
        if (! empty($galeri['thumbnail'])) {
            $uploader->delete('galeri', $galeri['thumbnail']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/galeri'))->with('success', 'Item galeri berhasil dihapus.');
    }
}
