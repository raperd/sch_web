<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PpdbModel;

class PpdbController extends BaseController
{
    private PpdbModel $model;

    public function __construct()
    {
        $this->model = new PpdbModel();
    }

    public function index(): string
    {
        $tipe = $this->request->getGet('tipe') ?? '';

        $builder = $this->model->orderBy('tipe', 'ASC')->orderBy('urutan', 'ASC');
        if ($tipe !== '') {
            $builder->where('tipe', $tipe);
        }

        return view('admin/ppdb/index', [
            'title'       => 'Konten PPDB',
            'konten'      => $builder->findAll(),
            'tipe_filter' => $tipe,
            'total'       => $this->model->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/ppdb/create', [
            'title' => 'Tambah Konten PPDB',
        ]);
    }

    public function store()
    {
        $rules = [
            'judul_blok' => 'required|max_length[255]',
            'tipe'       => 'required|in_list[persyaratan,jadwal,alur,faq,info]',
            'konten'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'judul_blok' => $this->request->getPost('judul_blok'),
            'konten'     => $this->request->getPost('konten'),
            'tipe'       => $this->request->getPost('tipe'),
            'urutan'     => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active'  => (int) ($this->request->getPost('is_active') === '1'),
        ]);

        return redirect()->to(base_url('admin/ppdb'))->with('success', 'Konten PPDB berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $konten = $this->model->find($id);
        if (! $konten) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Konten tidak ditemukan.');
        }

        return view('admin/ppdb/edit', [
            'title'  => 'Edit Konten PPDB',
            'konten' => $konten,
        ]);
    }

    public function update(int $id)
    {
        $konten = $this->model->find($id);
        if (! $konten) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Konten tidak ditemukan.');
        }

        $rules = [
            'judul_blok' => 'required|max_length[255]',
            'tipe'       => 'required|in_list[persyaratan,jadwal,alur,faq,info]',
            'konten'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'judul_blok' => $this->request->getPost('judul_blok'),
            'konten'     => $this->request->getPost('konten'),
            'tipe'       => $this->request->getPost('tipe'),
            'urutan'     => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active'  => (int) ($this->request->getPost('is_active') === '1'),
        ]);

        return redirect()->to(base_url('admin/ppdb'))->with('success', 'Konten PPDB berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->model->find($id)) {
            return redirect()->back()->with('error', 'Konten tidak ditemukan.');
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/ppdb'))->with('success', 'Konten PPDB berhasil dihapus.');
    }
}
