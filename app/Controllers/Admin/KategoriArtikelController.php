<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriArtikelModel;
use App\Models\ArtikelModel;

class KategoriArtikelController extends BaseController
{
    private KategoriArtikelModel $model;

    public function __construct()
    {
        $this->model = new KategoriArtikelModel();
    }

    public function index(): string
    {
        $artikelModel = new ArtikelModel();

        // Hitung jumlah artikel per kategori
        $counts = [];
        $rows   = $artikelModel->select('kategori_id, COUNT(*) as total')
                               ->groupBy('kategori_id')
                               ->findAll();
        foreach ($rows as $r) {
            $counts[$r['kategori_id']] = (int) $r['total'];
        }

        return view('admin/kategori_artikel/index', [
            'title'      => 'Kategori Artikel',
            'breadcrumb' => 'Kategori Artikel',
            'kategori'   => $this->model->orderBy('urutan', 'ASC')->orderBy('nama', 'ASC')->findAll(),
            'counts'     => $counts,
        ]);
    }

    public function create(): string
    {
        return view('admin/kategori_artikel/form', [
            'title'       => 'Tambah Kategori',
            'breadcrumb'  => 'Tambah Kategori',
            'kategori'    => null,
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'  => 'required|max_length[100]|is_unique[kategori_artikel.nama]',
            'slug'  => 'permit_empty|max_length[120]',
        ];

        $messages = [
            'nama' => [
                'required'  => 'Nama kategori wajib diisi.',
                'is_unique' => 'Nama kategori sudah digunakan.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = $this->request->getPost('nama');
        $slug = $this->request->getPost('slug');
        if (empty($slug)) {
            $slug = slug_generate($nama);
        }

        // Pastikan slug unik
        $existing = $this->model->where('slug', $slug)->first();
        if ($existing) {
            $slug .= '-' . time();
        }

        $this->model->insert([
            'nama'       => $nama,
            'slug'       => $slug,
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'urutan'     => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('kategori-artikel'))->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $kategori = $this->model->find($id);
        if (! $kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan.');
        }

        return view('admin/kategori_artikel/form', [
            'title'      => 'Edit Kategori',
            'breadcrumb' => 'Edit Kategori',
            'kategori'   => $kategori,
        ]);
    }

    public function update(int $id)
    {
        $kategori = $this->model->find($id);
        if (! $kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan.');
        }

        $rules = [
            'nama' => "required|max_length[100]|is_unique[kategori_artikel.nama,id,{$id}]",
            'slug' => 'permit_empty|max_length[120]',
        ];

        $messages = [
            'nama' => [
                'required'  => 'Nama kategori wajib diisi.',
                'is_unique' => 'Nama kategori sudah digunakan.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = $this->request->getPost('nama');
        $slug = $this->request->getPost('slug');
        if (empty($slug)) {
            $slug = slug_generate($nama);
        }

        // Pastikan slug unik (kecuali milik sendiri)
        $existing = $this->model->where('slug', $slug)->where('id !=', $id)->first();
        if ($existing) {
            $slug .= '-' . time();
        }

        $this->model->update($id, [
            'nama'      => $nama,
            'slug'      => $slug,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('kategori-artikel'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $kategori = $this->model->find($id);
        if (! $kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }

        // Cek apakah ada artikel yang menggunakan kategori ini
        $artikelModel = new ArtikelModel();
        $used = $artikelModel->where('kategori_id', $id)->countAllResults();
        if ($used > 0) {
            return redirect()->back()->with('error', "Kategori tidak dapat dihapus karena digunakan oleh {$used} artikel.");
        }

        $this->model->delete($id);

        return redirect()->to(admin_url('kategori-artikel'))->with('success', 'Kategori berhasil dihapus.');
    }
}
