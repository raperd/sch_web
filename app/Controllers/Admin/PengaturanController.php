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
        $fileFields = $this->request->getFiles('pengaturan_file') ?? [];
        foreach ($fileFields as $key => $file) {
            if ($file instanceof \CodeIgniter\HTTP\Files\UploadedFile
                && $file->isValid()
                && ! $file->hasMoved()
            ) {
                $dest = FCPATH . 'uploads/pengaturan/';
                if (! is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }
                $newName = $file->getRandomName();
                $file->move($dest, $newName);
                $this->model->setByKey($key, $newName);
            }
        }

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan berhasil disimpan.');
    }
}
