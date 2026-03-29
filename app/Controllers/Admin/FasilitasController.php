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
        return view('admin/fasilitas/create', [
            'title'       => 'Tambah Fasilitas',
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = ['nama' => 'required|max_length[100]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->_saveCroppedFoto();

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

        $foto    = $fasilitas['foto'];
        $newFoto = $this->_saveCroppedFoto();
        if ($newFoto) {
            if ($foto) {
                (new \App\Libraries\ImageUpload())->delete('fasilitas', $foto);
            }
            $foto = $newFoto;
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

    private function _saveCroppedFoto(): ?string
    {
        $b64 = $this->request->getPost('foto_cropped');
        if (empty($b64) || ! str_starts_with($b64, 'data:image/')) {
            return null;
        }
        [, $data] = explode(',', $b64, 2);
        $imgData  = base64_decode($data);
        if (! $imgData) return null;

        $dir = FCPATH . 'uploads/fasilitas/';
        if (! is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'foto_' . bin2hex(random_bytes(8)) . '.jpg';
        file_put_contents($dir . $filename, $imgData);
        return $filename;
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
