<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;

class PengaturanController extends BaseController
{
    private PengaturanModel $model;

    public function __construct()
    {
        $this->model = new PengaturanModel();
    }

    public function index(): string
    {
        return view('admin/pengaturan/index', [
            'title'   => 'Pengaturan Situs',
            'grouped' => $this->model->getAllGrouped(),
        ]);
    }

    public function update()
    {
        $data = $this->request->getPost('pengaturan') ?? [];

        foreach ($data as $key => $value) {
            $this->model->setByKey($key, $value);
        }

        // Handle image uploads (field name: pengaturan_file[setting_key])
        // Whitelist ekstensi dan MIME yang diizinkan untuk setting situs
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ico', 'svg'];
        $allowedMimes      = [
            'image/jpeg', 'image/png', 'image/gif',
            'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon',
            'image/svg+xml',
        ];

        $allFiles   = $this->request->getFiles();
        $fileFields = $allFiles['pengaturan_file'] ?? [];
        foreach ($fileFields as $key => $file) {
            if (! ($file instanceof \CodeIgniter\HTTP\Files\UploadedFile)
                || ! $file->isValid()
                || $file->hasMoved()
            ) {
                continue;
            }

            // Validasi ekstensi
            $ext = strtolower($file->getClientExtension());
            if (! in_array($ext, $allowedExtensions, true)) {
                session()->setFlashdata('error', "File '{$file->getClientName()}' ditolak: ekstensi tidak diizinkan.");
                continue;
            }

            // Validasi MIME type dari server (bukan dari client)
            $mime = $file->getMimeType();
            if (! in_array($mime, $allowedMimes, true)) {
                session()->setFlashdata('error', "File '{$file->getClientName()}' ditolak: tipe file tidak diizinkan.");
                continue;
            }

            // Batas ukuran 2 MB
            if ($file->getSize() > 2 * 1024 * 1024) {
                session()->setFlashdata('error', "File '{$file->getClientName()}' ditolak: ukuran melebihi 2 MB.");
                continue;
            }

            $dest = FCPATH . 'uploads/pengaturan/';
            if (! is_dir($dest)) {
                mkdir($dest, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($dest, $newName);

            // Hapus file lama jika ada
            $oldValue = $this->model->getByKey($key);
            if ($oldValue) {
                $oldPath = $dest . $oldValue;
                if (file_exists($oldPath)) @unlink($oldPath);
            }

            $this->model->setByKey($key, $newName);
        }

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan berhasil disimpan.');
    }
}
