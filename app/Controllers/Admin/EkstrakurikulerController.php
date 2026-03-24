<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EkstrakurikulerModel;

class EkstrakurikulerController extends BaseController
{
    private EkstrakurikulerModel $model;

    public function __construct()
    {
        $this->model = new EkstrakurikulerModel();
    }

    public function index(): string
    {
        return view('admin/ekskul/index', [
            'title'  => 'Ekstrakurikuler',
            'ekskul' => $this->model->orderBy('urutan', 'ASC')->findAll(),
            'total'  => $this->model->countAllResults(false),
            'aktif'  => $this->model->where('is_active', 1)->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/ekskul/create', ['title' => 'Tambah Ekstrakurikuler']);
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
            $foto = $uploader->upload('foto', 'ekskul');
        }

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto'      => $foto,
            'jadwal'    => $this->request->getPost('jadwal'),
            'pembina'   => $this->request->getPost('pembina'),
            'prestasi'  => $this->request->getPost('prestasi'),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/ekskul'))->with('success', 'Ekstrakurikuler berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $ekskul = $this->model->find($id);
        if (! $ekskul) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        return view('admin/ekskul/edit', ['title' => 'Edit Ekstrakurikuler', 'ekskul' => $ekskul]);
    }

    public function update(int $id)
    {
        $ekskul = $this->model->find($id);
        if (! $ekskul) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        $rules = ['nama' => 'required|max_length[100]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $ekskul['foto'];
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $newFoto  = $uploader->upload('foto', 'ekskul');
            if ($newFoto) {
                if ($foto) {
                    $uploader->delete('ekskul', $foto);
                }
                $foto = $newFoto;
            }
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto'      => $foto,
            'jadwal'    => $this->request->getPost('jadwal'),
            'pembina'   => $this->request->getPost('pembina'),
            'prestasi'  => $this->request->getPost('prestasi'),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/ekskul'))->with('success', 'Data berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $ekskul = $this->model->find($id);
        if (! $ekskul) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if (! empty($ekskul['foto'])) {
            (new \App\Libraries\ImageUpload())->delete('ekskul', $ekskul['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/ekskul'))->with('success', 'Data berhasil dihapus.');
    }
}
