<?php

namespace Tests\Feature;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Verifikasi semua 7 route publik mengembalikan HTTP 200.
 * Menggunakan database utama (sudah termigrasi dan di-seed).
 *
 * @internal
 */
final class PublicRoutesTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $refresh   = false;
    protected $namespace = 'App';

    /**
     * @dataProvider publicRouteProvider
     */
    public function testPublicRouteReturns200(string $url): void
    {
        $result = $this->get($url);
        $result->assertStatus(200);
    }

    public static function publicRouteProvider(): array
    {
        return [
            'Beranda'         => ['/'],
            'Profil'          => ['/profil'],
            'Akademik'        => ['/akademik'],
            'Kehidupan Siswa' => ['/kehidupan-siswa'],
            'Direktori'       => ['/direktori'],
            'Berita'          => ['/berita'],
            'PPDB'            => ['/ppdb'],
        ];
    }

    public function testBeritaDetailReturns404ForInvalidSlug(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->get('/berita/slug-yang-tidak-ada-xyz999999');
    }
}
