<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AplikasiModel;

class AplikasiController extends BaseController
{
    private AplikasiModel $model;

    public function __construct()
    {
        $this->model = new AplikasiModel();
    }

    public function index(): string
    {
        return view('admin/aplikasi/index', [
            'title'      => 'Link Terkait',
            'breadcrumb' => 'Link Terkait',
            'apps'       => $this->model->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('admin/aplikasi/form', [
            'title'       => 'Tambah Link Baru',
            'breadcrumb'  => 'Link Terkait',
            'app'         => null,
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        if (! $this->validate([
            'nama' => 'required|max_length[255]',
            'url'  => 'required|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'url'       => $this->request->getPost('url'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->_saveCroppedIcon(),
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active' => $this->request->getPost('is_active') === '1' ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/aplikasi'))->with('success', 'Link berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $app = $this->model->find($id);
        if (! $app) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('admin/aplikasi/form', [
            'title'      => 'Edit Link Terkait',
            'breadcrumb' => 'Link Terkait',
            'app'        => $app,
        ]);
    }

    public function update(int $id)
    {
        $app = $this->model->find($id);
        if (! $app) return redirect()->back()->with('error', 'Link tidak ditemukan.');

        if (! $this->validate([
            'nama' => 'required|max_length[255]',
            'url'  => 'required|max_length[255]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $iconName  = $app['icon'];
        $newIcon   = $this->_saveCroppedIcon();
        if ($newIcon) {
            if ($iconName && file_exists(FCPATH . 'uploads/aplikasi/' . $iconName)) {
                @unlink(FCPATH . 'uploads/aplikasi/' . $iconName);
            }
            $iconName = $newIcon;
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'url'       => $this->request->getPost('url'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $iconName,
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active' => $this->request->getPost('is_active') === '1' ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/aplikasi'))->with('success', 'Link berhasil diperbarui.');
    }

    public function toggleActive(int $id)
    {
        $app = $this->model->find($id);
        if (! $app) {
            return $this->response->setJSON(['ok' => false, 'message' => 'Link tidak ditemukan.']);
        }

        $newStatus = $app['is_active'] == 1 ? 0 : 1;
        $this->model->update($id, ['is_active' => $newStatus]);

        return $this->response->setJSON([
                'ok'        => true,
                'is_active' => $newStatus,
                'csrf'      => \Config\Services::security()->getCSRFHash(),
            ]);
    }

    private function _saveCroppedIcon(): ?string
    {
        $b64 = $this->request->getPost('icon_cropped');
        if (empty($b64) || ! str_starts_with($b64, 'data:image/')) {
            return null;
        }
        [, $data] = explode(',', $b64, 2);
        $imgData  = base64_decode($data);
        if (! $imgData) return null;

        $dir = FCPATH . 'uploads/aplikasi/';
        if (! is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'icon_' . bin2hex(random_bytes(8)) . '.png';
        file_put_contents($dir . $filename, $imgData);
        return $filename;
    }

    public function delete(int $id)
    {
        $app = $this->model->find($id);
        if (! $app) return redirect()->back()->with('error', 'Link tidak ditemukan.');
        
        if ($app['icon'] && file_exists(FCPATH . 'uploads/aplikasi/' . $app['icon'])) {
            @unlink(FCPATH . 'uploads/aplikasi/' . $app['icon']);
        }
        
        $this->model->delete($id);
        return redirect()->to(base_url('admin/aplikasi'))->with('success', 'Link berhasil dihapus.');
    }
}
