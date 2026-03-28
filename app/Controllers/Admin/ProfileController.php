<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * Halaman profil & ganti password pengguna yang sedang login
     */
    public function index(): string
    {
        $userId = (int) session('admin_id');
        $user   = $this->model->find($userId);

        if (! $user) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Sesi tidak valid.');
        }

        return view('admin/profile/index', [
            'title'      => 'Profil Saya',
            'breadcrumb' => 'Profil',
            'user'       => $user,
        ]);
    }

    /**
     * Proses ganti password
     */
    public function changePassword()
    {
        $userId = (int) session('admin_id');
        $user   = $this->model->find($userId);

        if (! $user) {
            return redirect()->to(base_url('admin/login'));
        }

        $rules = [
            'password_lama'  => 'required',
            'password_baru'  => 'required|min_length[8]',
            'konfirmasi'     => 'required|matches[password_baru]',
        ];

        $messages = [
            'password_lama'  => ['required' => 'Password lama wajib diisi.'],
            'password_baru'  => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal 8 karakter.',
            ],
            'konfirmasi'     => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verifikasi password lama
        $passwordLama = $this->request->getPost('password_lama');
        if (! password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password lama tidak sesuai.');
        }

        // Update password baru
        $this->model->update($userId, [
            'password' => $this->request->getPost('password_baru'),
        ]);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Password berhasil diubah. Silakan login kembali jika diperlukan.');
    }

    /**
     * Update foto profil (avatar)
     */
    public function updateAvatar()
    {
        $userId = (int) session('admin_id');
        $user   = $this->model->find($userId);
        if (! $user) {
            return redirect()->to(base_url('admin/login'));
        }

        $avatar = $this->_saveCroppedAvatar($userId);

        if (! $avatar) {
            return redirect()->back()->with('error', 'Gagal menyimpan foto profil.');
        }

        // Hapus avatar lama jika ada
        if (! empty($user['avatar'])) {
            $old = FCPATH . 'uploads/users/' . $user['avatar'];
            if (file_exists($old)) unlink($old);
        }

        $this->model->update($userId, ['avatar' => $avatar]);
        session()->set('admin_avatar', $avatar);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Hapus foto profil
     */
    public function deleteAvatar()
    {
        $userId = (int) session('admin_id');
        $user   = $this->model->find($userId);
        if (! $user) {
            return redirect()->to(base_url('admin/login'));
        }

        if (! empty($user['avatar'])) {
            $path = FCPATH . 'uploads/users/' . $user['avatar'];
            if (file_exists($path)) unlink($path);
        }

        $this->model->update($userId, ['avatar' => null]);
        session()->set('admin_avatar', null);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Foto profil dihapus.');
    }

    private function _saveCroppedAvatar(int $userId): ?string
    {
        $b64 = $this->request->getPost('avatar_cropped');
        if (empty($b64) || ! str_contains($b64, 'base64,')) return null;

        [, $data] = explode('base64,', $b64);
        $img = imagecreatefromstring(base64_decode($data));
        if (! $img) return null;

        $dir = FCPATH . 'uploads/users/';
        if (! is_dir($dir)) mkdir($dir, 0755, true);

        $filename = 'avatar_' . $userId . '_' . time() . '.jpg';
        $w = imagesx($img); $h = imagesy($img);
        $out = imagecreatetruecolor(200, 200);
        imagecopyresampled($out, $img, 0, 0, 0, 0, 200, 200, $w, $h);
        imagejpeg($out, $dir . $filename, 88);
        imagedestroy($img); imagedestroy($out);
        return $filename;
    }

    /**
     * Update info profil (nama & email)
     */
    public function updateInfo()
    {
        $userId = (int) session('admin_id');
        $user   = $this->model->find($userId);

        if (! $user) {
            return redirect()->to(base_url('admin/login'));
        }

        $rules = [
            'nama'  => 'required|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($userId, [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ]);

        // Update session nama
        session()->set('admin_nama', $this->request->getPost('nama'));

        return redirect()->to(base_url('admin/profile'))->with('success', 'Informasi profil berhasil diperbarui.');
    }
}
