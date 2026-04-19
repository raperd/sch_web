<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\QuickLinkModel;

class MenuController extends BaseController
{
    private MenuModel $model;
    private QuickLinkModel $quickLinkModel;

    public function __construct()
    {
        $this->model          = new MenuModel();
        $this->quickLinkModel = new QuickLinkModel();
    }

    public function index(): string
    {
        return view('admin/menu/index', [
            'title'       => 'Manajemen Menu',
            'menus_publik'=> $this->model->where('lokasi', 'publik')->orderBy('urutan', 'ASC')->findAll(),
            'menus_footer'=> $this->model->where('lokasi', 'footer')->orderBy('urutan', 'ASC')->findAll(),
            'quick_links' => $this->quickLinkModel->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'   => 'required|max_length[100]',
            'url'    => 'required|max_length[255]',
            'lokasi' => 'required|in_list[publik,footer,admin]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'nama'      => $this->request->getPost('nama'),
            'url'       => $this->request->getPost('url'),
            'icon'      => $this->request->getPost('icon'),
            'target'    => $this->request->getPost('target') ?: '_self',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
            'lokasi'    => $this->request->getPost('lokasi'),
        ]);

        return redirect()->to(admin_url('menu'))->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $menu = $this->model->find($id);
        if (! $menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'url'       => $this->request->getPost('url'),
            'icon'      => $this->request->getPost('icon'),
            'target'    => $this->request->getPost('target') ?: '_self',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
        ]);

        return redirect()->to(admin_url('menu'))->with('success', 'Menu berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->model->find($id)) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        $this->model->delete($id);
        return redirect()->to(admin_url('menu'))->with('success', 'Menu berhasil dihapus.');
    }

    public function updateUrutan()
    {
        $body = $this->request->getJSON(true);
        if (! is_array($body)) {
            return $this->response->setJSON(['success' => false]);
        }

        foreach ($body as $item) {
            if (isset($item['id'], $item['urutan'])) {
                $this->model->update((int) $item['id'], ['urutan' => (int) $item['urutan']]);
            }
        }

        return $this->response->setJSON(['success' => true]);
    }
}
