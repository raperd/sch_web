<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;

class FasilitasController extends BaseController
{
    private FasilitasModel $model;

    public function __construct()
    {
        $this->model = new FasilitasModel();
    }

    public function index(): string
    {
        return view('admin/fasilitas/index', [
            'title'     => 'Fasilitas Sekolah',
            'fasilitas' => $this->model->orderBy('urutan', 'ASC')->findAll(),
            'total'     => $this->model->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/fasilitas/create', ['title' => 'Tambah Fasilitas']);
    }

    public function store()
    {
        $rules = ['nama' => 'required|max_length[100]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = null;
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $foto = $uploader->upload('foto', 'fasilitas');
        }

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->request->getPost('icon'),
            'foto'      => $foto,
            'jumlah'    => (int) ($this->request->getPost('jumlah') ?: 0),
            'kondisi'   => $this->request->getPost('kondisi') ?: 'baik',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/fasilitas'))->with('success', 'Fasilitas berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $fasilitas = $this->model->find($id);
        if (! $fasilitas) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        return view('admin/fasilitas/edit', ['title' => 'Edit Fasilitas', 'fasilitas' => $fasilitas]);
    }

    public function update(int $id)
    {
        $fasilitas = $this->model->find($id);
        if (! $fasilitas) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        $rules = ['nama' => 'required|max_length[100]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $fasilitas['foto'];
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $newFoto  = $uploader->upload('foto', 'fasilitas');
            if ($newFoto) {
                if ($foto) {
                    $uploader->delete('fasilitas', $foto);
                }
                $foto = $newFoto;
            }
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->request->getPost('icon'),
            'foto'      => $foto,
            'jumlah'    => (int) ($this->request->getPost('jumlah') ?: 0),
            'kondisi'   => $this->request->getPost('kondisi') ?: 'baik',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/fasilitas'))->with('success', 'Data berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $fasilitas = $this->model->find($id);
        if (! $fasilitas) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if (! empty($fasilitas['foto'])) {
            (new \App\Libraries\ImageUpload())->delete('fasilitas', $fasilitas['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/fasilitas'))->with('success', 'Data berhasil dihapus.');
    }
}
