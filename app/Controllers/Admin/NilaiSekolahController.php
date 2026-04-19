<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NilaiSekolahModel;

class NilaiSekolahController extends BaseController
{
    private NilaiSekolahModel $model;

    public function __construct()
    {
        $this->model = new NilaiSekolahModel();
    }

    public function index(): string
    {
        return view('admin/nilai_sekolah/index', [
            'title'      => 'Nilai Sekolah',
            'breadcrumb' => 'Nilai Sekolah',
            'nilai'      => $this->model->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('admin/nilai_sekolah/form', [
            'title'       => 'Tambah Nilai Sekolah',
            'breadcrumb'  => 'Nilai Sekolah',
            'nilai'       => null,
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        if (! $this->validate([
            'nama'      => 'required|max_length[255]',
            'deskripsi' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->request->getPost('icon') ?: 'bi-award',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('nilai-sekolah'))->with('success', 'Nilai sekolah berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $nilai = $this->model->find($id);
        if (! $nilai) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('admin/nilai_sekolah/form', [
            'title'      => 'Edit Nilai Sekolah',
            'breadcrumb' => 'Nilai Sekolah',
            'nilai'      => $nilai,
        ]);
    }

    public function update(int $id)
    {
        $nilai = $this->model->find($id);
        if (! $nilai) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if (! $this->validate([
            'nama'      => 'required|max_length[255]',
            'deskripsi' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->request->getPost('icon') ?: 'bi-award',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('nilai-sekolah'))->with('success', 'Nilai sekolah berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $nilai = $this->model->find($id);
        if (! $nilai) return redirect()->back()->with('error', 'Data tidak ditemukan.');
        $this->model->delete($id);
        return redirect()->to(admin_url('nilai-sekolah'))->with('success', 'Nilai sekolah berhasil dihapus.');
    }
}
