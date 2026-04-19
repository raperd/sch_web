<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QuickLinkModel;

class QuickLinkController extends BaseController
{
    private QuickLinkModel $model;

    public function __construct()
    {
        $this->model = new QuickLinkModel();
    }

    public function index(): string
    {
        return view('admin/quick_links/index', [
            'title'      => 'Quick Links',
            'breadcrumb' => 'Quick Links',
            'links'      => $this->model->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('admin/quick_links/form', [
            'title'       => 'Tambah Quick Link',
            'breadcrumb'  => 'Quick Links',
            'link'        => null,
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        if (! $this->validate([
            'label' => 'required|max_length[100]',
            'url'   => 'required|max_length[255]',
            'icon'  => 'required|max_length[100]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'label'     => $this->request->getPost('label'),
            'url'       => $this->request->getPost('url'),
            'icon'      => trim($this->request->getPost('icon')),
            'warna'     => $this->request->getPost('warna') ?: 'primary',
            'target'    => $this->request->getPost('target') === '_blank' ? '_blank' : '_self',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active' => $this->request->getPost('is_active') === '1' ? 1 : 0,
        ]);

        return redirect()->to(admin_url('quick-links'))->with('success', 'Quick link berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $link = $this->model->find($id);
        if (! $link) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Quick link tidak ditemukan.');
        }
        return view('admin/quick_links/form', [
            'title'      => 'Edit Quick Link',
            'breadcrumb' => 'Quick Links',
            'link'       => $link,
        ]);
    }

    public function update(int $id)
    {
        $link = $this->model->find($id);
        if (! $link) {
            return redirect()->back()->with('error', 'Quick link tidak ditemukan.');
        }

        if (! $this->validate([
            'label' => 'required|max_length[100]',
            'url'   => 'required|max_length[255]',
            'icon'  => 'required|max_length[100]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'label'     => $this->request->getPost('label'),
            'url'       => $this->request->getPost('url'),
            'icon'      => trim($this->request->getPost('icon')),
            'warna'     => $this->request->getPost('warna') ?: 'primary',
            'target'    => $this->request->getPost('target') === '_blank' ? '_blank' : '_self',
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
            'is_active' => $this->request->getPost('is_active') === '1' ? 1 : 0,
        ]);

        return redirect()->to(admin_url('quick-links'))->with('success', 'Quick link berhasil diperbarui.');
    }

    public function toggleActive(int $id)
    {
        $link = $this->model->find($id);
        if (! $link) {
            return redirect()->back()->with('error', 'Quick link tidak ditemukan.');
        }
        $this->model->update($id, ['is_active' => $link['is_active'] ? 0 : 1]);
        return redirect()->back()->with('success', 'Status quick link diperbarui.');
    }

    public function delete(int $id)
    {
        $link = $this->model->find($id);
        if (! $link) {
            return redirect()->back()->with('error', 'Quick link tidak ditemukan.');
        }
        $this->model->delete($id);
        return redirect()->to(admin_url('quick-links'))->with('success', 'Quick link berhasil dihapus.');
    }
}
