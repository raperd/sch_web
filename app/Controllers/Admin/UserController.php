<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index(): string
    {
        return view('admin/users/index', [
            'title'      => 'Manajemen Pengguna',
            'breadcrumb' => 'Pengguna',
            'users'      => $this->model->orderBy('role', 'ASC')->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('admin/users/form', [
            'title'      => 'Tambah Pengguna',
            'breadcrumb' => 'Tambah Pengguna',
            'user'       => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|max_length[100]',
            'username' => 'required|max_length[50]|is_unique[users.username]',
            'email'    => 'permit_empty|valid_email|max_length[100]',
            'password' => 'required|min_length[8]',
            'role'     => 'required|in_list[admin,kontributor]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'role'      => $this->request->getPost('role'),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
        ]);

        return redirect()->to(admin_url('users'))->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $user = $this->model->find($id);
        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengguna tidak ditemukan.');
        }

        return view('admin/users/form', [
            'title'      => 'Edit Pengguna',
            'breadcrumb' => 'Edit Pengguna',
            'user'       => $user,
        ]);
    }

    public function update(int $id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengguna tidak ditemukan.');
        }

        $rules = [
            'nama'     => 'required|max_length[100]',
            'username' => "required|max_length[50]|is_unique[users.username,id,{$id}]",
            'email'    => 'permit_empty|valid_email|max_length[100]',
            'password' => 'permit_empty|min_length[8]',
            'role'     => 'required|in_list[admin,kontributor]',
        ];

        // Superadmin role cannot be changed through this UI
        if ($user['role'] === 'superadmin') {
            return redirect()->back()->with('error', 'Role superadmin tidak dapat diubah.');
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'      => $this->request->getPost('nama'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'role'      => $this->request->getPost('role'),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (! empty($password)) {
            $data['password'] = $password;
        }

        $this->model->update($id, $data);

        return redirect()->to(admin_url('users'))->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        // Cannot delete superadmin
        if ($user['role'] === 'superadmin') {
            return redirect()->back()->with('error', 'Akun superadmin tidak dapat dihapus.');
        }

        // Cannot delete self
        if ($id === (int) session('admin_id')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $this->model->delete($id);

        return redirect()->to(admin_url('users'))->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleActive(int $id)
    {
        $user = $this->model->find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        // Cannot deactivate superadmin
        if ($user['role'] === 'superadmin') {
            return redirect()->back()->with('error', 'Akun superadmin tidak dapat dinonaktifkan.');
        }

        // Cannot deactivate self
        if ($id === (int) session('admin_id')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $this->model->update($id, ['is_active' => $newStatus]);
        $msg = $newStatus ? 'Pengguna diaktifkan.' : 'Pengguna dinonaktifkan.';

        return redirect()->back()->with('success', $msg);
    }
}
