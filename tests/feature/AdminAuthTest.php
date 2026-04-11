<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Verifikasi proteksi admin panel dan proses login.
 * Menggunakan database utama (sudah termigrasi dan di-seed).
 *
 * @internal
 */
final class AdminAuthTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate      = true;
    protected $migrateOnce  = true;
    protected $seed         = 'App\Database\Seeds\DatabaseSeeder';
    protected $seedOnce     = true;
    protected $namespace    = 'App';

    public function testAdminDashboardRedirectsWhenNotLoggedIn(): void
    {
        $result = $this->get('/admin/dashboard');
        $result->assertRedirectTo(site_url('admin/login'));
    }

    public function testAdminArtikelRedirectsWhenNotLoggedIn(): void
    {
        $result = $this->get('/admin/artikel');
        $result->assertRedirectTo(site_url('admin/login'));
    }

    public function testAdminGuruRedirectsWhenNotLoggedIn(): void
    {
        $result = $this->get('/admin/guru');
        $result->assertRedirectTo(site_url('admin/login'));
    }

    public function testLoginPageReturns200(): void
    {
        $result = $this->get('/admin/login');
        $result->assertStatus(200);
    }

    public function testLoginWithWrongPasswordRedirectsToLogin(): void
    {
        $result = $this->post('/admin/login', [
            'username' => 'superadmin',
            'password' => 'password_salah_banget_xyz',
        ]);

        // Harus redirect kembali ke login, bukan ke dashboard
        $this->assertTrue($result->isRedirect());
        $redirectUrl = $result->getRedirectUrl();
        $this->assertStringContainsString('admin/login', $redirectUrl);
    }

    public function testLoginWithCorrectCredentials(): void
    {
        $result = $this->post('/admin/login', [
            'username' => 'superadmin',
            'password' => 'Admin@123!',
        ]);

        $this->assertTrue($result->isRedirect());
        $this->assertStringContainsString('admin/dashboard', $result->getRedirectUrl());
    }
}
