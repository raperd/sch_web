<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProgramUnggulanModel;
use App\Models\KurikulumBlokModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AkademikController extends BaseController
{
    protected ProgramUnggulanModel $programModel;
    protected KurikulumBlokModel   $kurikulumModel;

    public function __construct()
    {
        $this->programModel    = new ProgramUnggulanModel();
        $this->kurikulumModel  = new KurikulumBlokModel();
    }

    // =========================================================
    //  PROGRAM UNGGULAN
    // =========================================================

    public function program(): string
    {
        return view('admin/akademik/program', [
            'title'    => 'Program Unggulan',
            'programs' => $this->programModel->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function programCreate(): string
    {
        return view('admin/akademik/program_form', [
            'title'       => 'Tambah Program Unggulan',
            'program'     => null,
            'next_urutan' => ($this->programModel->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function programStore(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'judul'     => 'required|max_length[150]',
            'deskripsi' => 'permit_empty',
            'icon'      => 'required|max_length[60]',
            'warna'     => 'required|max_length[30]',
            'urutan'    => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->programModel->insert([
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->request->getPost('icon'),
            'warna'     => $this->request->getPost('warna'),
            'urutan'    => (int) $this->request->getPost('urutan'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        session()->setFlashdata('success', 'Program unggulan berhasil ditambahkan.');
        return redirect()->to('/admin/akademik/program');
    }

    public function programEdit(int $id): string
    {
        $program = $this->programModel->find($id) ?? throw new PageNotFoundException('Program tidak ditemukan');
        return view('admin/akademik/program_form', [
            'title'   => 'Edit Program Unggulan',
            'program' => $program,
        ]);
    }

    public function programUpdate(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->programModel->find($id) ?? throw new PageNotFoundException('Program tidak ditemukan');

        $rules = [
            'judul'  => 'required|max_length[150]',
            'icon'   => 'required|max_length[60]',
            'warna'  => 'required|max_length[30]',
            'urutan' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->programModel->update($id, [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'icon'      => $this->request->getPost('icon'),
            'warna'     => $this->request->getPost('warna'),
            'urutan'    => (int) $this->request->getPost('urutan'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        session()->setFlashdata('success', 'Program unggulan berhasil diperbarui.');
        return redirect()->to('/admin/akademik/program');
    }

    public function programDelete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->programModel->find($id) ?? throw new PageNotFoundException('Program tidak ditemukan');
        $this->programModel->delete($id);
        session()->setFlashdata('success', 'Program unggulan dihapus.');
        return redirect()->to('/admin/akademik/program');
    }

    // =========================================================
    //  KURIKULUM BLOK
    // =========================================================

    public function kurikulum(): string
    {
        return view('admin/akademik/kurikulum', [
            'title' => 'Blok Kurikulum',
            'bloks' => $this->kurikulumModel->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function kurikulumCreate(): string
    {
        return view('admin/akademik/kurikulum_form', [
            'title'       => 'Tambah Blok Kurikulum',
            'blok'        => null,
            'next_urutan' => ($this->kurikulumModel->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function kurikulumStore(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'judul'  => 'required|max_length[150]',
            'konten' => 'permit_empty',
            'urutan' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kurikulumModel->insert([
            'judul'     => $this->request->getPost('judul'),
            'konten'    => $this->request->getPost('konten'),
            'urutan'    => (int) $this->request->getPost('urutan'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        session()->setFlashdata('success', 'Blok kurikulum berhasil ditambahkan.');
        return redirect()->to('/admin/akademik/kurikulum');
    }

    public function kurikulumEdit(int $id): string
    {
        $blok = $this->kurikulumModel->find($id) ?? throw new PageNotFoundException('Blok tidak ditemukan');
        return view('admin/akademik/kurikulum_form', [
            'title' => 'Edit Blok Kurikulum',
            'blok'  => $blok,
        ]);
    }

    public function kurikulumUpdate(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->kurikulumModel->find($id) ?? throw new PageNotFoundException('Blok tidak ditemukan');

        $rules = [
            'judul'  => 'required|max_length[150]',
            'urutan' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kurikulumModel->update($id, [
            'judul'     => $this->request->getPost('judul'),
            'konten'    => $this->request->getPost('konten'),
            'urutan'    => (int) $this->request->getPost('urutan'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        session()->setFlashdata('success', 'Blok kurikulum berhasil diperbarui.');
        return redirect()->to('/admin/akademik/kurikulum');
    }

    public function kurikulumDelete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->kurikulumModel->find($id) ?? throw new PageNotFoundException('Blok tidak ditemukan');
        $this->kurikulumModel->delete($id);
        session()->setFlashdata('success', 'Blok kurikulum dihapus.');
        return redirect()->to('/admin/akademik/kurikulum');
    }
}
