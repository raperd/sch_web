<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KepalaSekolahModel;
use App\Libraries\ImageUpload;

class KepalaSekolahController extends BaseController
{
    private KepalaSekolahModel $model;

    public function __construct()
    {
        $this->model = new KepalaSekolahModel();
    }

    public function index(): string
    {
        return view('admin/kepala_sekolah/index', [
            'title'      => 'Kepala Sekolah dari Masa ke Masa',
            'breadcrumb' => 'Kepala Sekolah',
            'list'       => $this->model->getAllOrdered(),
        ]);
    }

    public function create(): string
    {
        return view('admin/kepala_sekolah/form', [
            'title'       => 'Tambah Kepala Sekolah',
            'breadcrumb'  => 'Tambah Kepala Sekolah',
            'item'        => null,
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'          => 'required|max_length[150]',
            'periode_mulai' => 'required|integer|min_length[4]|max_length[4]',
            'periode_selesai' => 'permit_empty|integer|min_length[4]|max_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = null;
        // Coba foto dari crop (base64)
        $b64 = $this->request->getPost('foto_cropped');
        if ($b64 && str_starts_with($b64, 'data:image/')) {
            $foto = $this->_saveCroppedFoto($b64);
        }
        // Fallback file upload biasa
        if (! $foto) {
            $file = $this->request->getFile('foto');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $uploader = new ImageUpload();
                $foto = $uploader->upload('foto', 'kepala_sekolah');
            }
        }

        $periodeSelesai = $this->request->getPost('periode_selesai');

        $this->model->insert([
            'nama'            => $this->request->getPost('nama'),
            'foto'            => $foto,
            'periode_mulai'   => (int) $this->request->getPost('periode_mulai'),
            'periode_selesai' => $periodeSelesai ? (int) $periodeSelesai : null,
            'gelar_depan'     => $this->request->getPost('gelar_depan') ?: null,
            'gelar_belakang'  => $this->request->getPost('gelar_belakang') ?: null,
            'keterangan'      => $this->request->getPost('keterangan') ?: null,
            'urutan'          => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('kepala-sekolah'))->with('success', 'Data kepala sekolah berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $item = $this->model->find($id);
        if (! $item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        return view('admin/kepala_sekolah/form', [
            'title'      => 'Edit Kepala Sekolah',
            'breadcrumb' => 'Edit Kepala Sekolah',
            'item'       => $item,
        ]);
    }

    public function update(int $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        $rules = [
            'nama'            => 'required|max_length[150]',
            'periode_mulai'   => 'required|integer|min_length[4]|max_length[4]',
            'periode_selesai' => 'permit_empty|integer|min_length[4]|max_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploader = new ImageUpload();
        $foto = $item['foto'];

        // Coba foto dari crop (base64)
        $b64 = $this->request->getPost('foto_cropped');
        if ($b64 && str_starts_with($b64, 'data:image/')) {
            $newFoto = $this->_saveCroppedFoto($b64);
            if ($newFoto) {
                if ($foto) $uploader->delete('kepala_sekolah', $foto);
                $foto = $newFoto;
            }
        } else {
            // Fallback file upload biasa
            $file = $this->request->getFile('foto');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $newFoto = $uploader->upload('foto', 'kepala_sekolah');
                if ($newFoto) {
                    if ($foto) $uploader->delete('kepala_sekolah', $foto);
                    $foto = $newFoto;
                }
            }
        }

        $periodeSelesai = $this->request->getPost('periode_selesai');

        $this->model->update($id, [
            'nama'            => $this->request->getPost('nama'),
            'foto'            => $foto,
            'periode_mulai'   => (int) $this->request->getPost('periode_mulai'),
            'periode_selesai' => $periodeSelesai ? (int) $periodeSelesai : null,
            'gelar_depan'     => $this->request->getPost('gelar_depan') ?: null,
            'gelar_belakang'  => $this->request->getPost('gelar_belakang') ?: null,
            'keterangan'      => $this->request->getPost('keterangan') ?: null,
            'urutan'          => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('kepala-sekolah'))->with('success', 'Data berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if (! empty($item['foto'])) {
            (new ImageUpload())->delete('kepala_sekolah', $item['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(admin_url('kepala-sekolah'))->with('success', 'Data berhasil dihapus.');
    }

    private function _saveCroppedFoto(string $b64): ?string
    {
        [, $data] = explode(',', $b64, 2);
        $imgData  = base64_decode($data);
        if (! $imgData) return null;

        $dir = FCPATH . 'uploads/kepala_sekolah/';
        if (! is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'kepsek_' . bin2hex(random_bytes(8)) . '.jpg';
        file_put_contents($dir . $filename, $imgData);
        return $filename;
    }
}
