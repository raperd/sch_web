<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login(): string
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to(admin_url('dashboard'));
        }

        return view('admin/auth/login', [
            'title' => 'Login Admin',
        ]);
    }

    public function attemptLogin(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model    = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->findByUsername($username);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->to(admin_url('login'))->withInput()->with('error', 'Username atau password salah.');
        }

        // Set session
        session()->set([
            'admin_logged_in' => true,
            'admin_id'        => $user['id'],
            'admin_nama'      => $user['nama'],
            'admin_username'  => $user['username'],
            'admin_role'      => $user['role'],
            'admin_avatar'    => $user['avatar'] ?? null,
        ]);

        $model->updateLastLogin($user['id']);

        return redirect()->to(admin_url('dashboard'))->with('success', 'Selamat datang, ' . $user['nama'] . '!');
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();

        return redirect()->to(admin_url('login'))->with('success', 'Anda telah berhasil logout.');
    }
}
