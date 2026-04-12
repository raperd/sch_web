<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMenuPublik();
        $this->seedMenuFooter();
        $this->seedQuickLinks();
        $this->seedKategoriArtikel();
        $this->seedKategoriGaleri();
        $this->seedBidangGuru();
    }

    // ────────────────────────────────────────────────────────────────
    private function seedMenuPublik(): void
    {
        $items = [
            ['Beranda',               '/',                'bi-house',           1],
            ['Profil & Fasilitas',    '/profil',          'bi-building',        2],
            ['Akademik',              '/akademik',        'bi-book',            3],
            ['Kehidupan Siswa',       '/kehidupan-siswa', 'bi-people',          4],
            ['Direktori Guru & Staf', '/direktori',       'bi-person-badge',    5],
            ['Berita & Artikel',      '/berita',          'bi-newspaper',       6],
            ['SPMB',                  '/ppdb',            'bi-clipboard-check', 7],
        ];

        foreach ($items as [$nama, $url, $icon, $urutan]) {
            $exists = $this->db->table('menu')
                               ->getWhere(['url' => $url, 'lokasi' => 'publik'])
                               ->getRow();
            if (! $exists) {
                $this->db->table('menu')->insert([
                    'parent_id' => null,
                    'nama'      => $nama,
                    'url'       => $url,
                    'icon'      => $icon,
                    'target'    => '_self',
                    'urutan'    => $urutan,
                    'is_active' => 1,
                    'lokasi'    => 'publik',
                ]);
            }
        }
    }

    // ────────────────────────────────────────────────────────────────
    private function seedMenuFooter(): void
    {
        $items = [
            ['Beranda',          '/',             1],
            ['Profil Sekolah',   '/profil',       2],
            ['Berita & Artikel', '/berita',       3],
            ['SPMB',             '/ppdb',         4],
            ['Link Terkait',     '/link-terkait', 5],
        ];

        foreach ($items as [$nama, $url, $urutan]) {
            $exists = $this->db->table('menu')
                               ->getWhere(['url' => $url, 'lokasi' => 'footer'])
                               ->getRow();
            if (! $exists) {
                $this->db->table('menu')->insert([
                    'parent_id' => null,
                    'nama'      => $nama,
                    'url'       => $url,
                    'icon'      => null,
                    'target'    => '_self',
                    'urutan'    => $urutan,
                    'is_active' => 1,
                    'lokasi'    => 'footer',
                ]);
            }
        }
    }

    // ────────────────────────────────────────────────────────────────
    private function seedQuickLinks(): void
    {
        $items = [
            ['SPMB 2026/2027',  '/ppdb',             'bi-clipboard-check', 'primary',   1],
            ['Akademik',        '/akademik',          'bi-book-half',       'success',   2],
            ['Ekstrakurikuler', '/kehidupan-siswa',   'bi-trophy',          'warning',   3],
            ['Direktori Guru',  '/direktori',         'bi-person-badge',    'info',      4],
            ['Galeri Sekolah',  '/profil#tab-galeri', 'bi-images',          'secondary', 5],
            ['Berita Terbaru',  '/berita',            'bi-newspaper',       'danger',    6],
        ];

        foreach ($items as [$label, $url, $icon, $warna, $urutan]) {
            $exists = $this->db->table('quick_links')
                               ->getWhere(['url' => $url])
                               ->getRow();
            if (! $exists) {
                $this->db->table('quick_links')->insert([
                    'label'     => $label,
                    'url'       => $url,
                    'icon'      => $icon,
                    'warna'     => $warna,
                    'target'    => '_self',
                    'urutan'    => $urutan,
                    'is_active' => 1,
                ]);
            }
        }
    }

    // ────────────────────────────────────────────────────────────────
    private function seedKategoriArtikel(): void
    {
        $items = [
            ['Berita',       'berita',       1],
            ['Pengumuman',   'pengumuman',   2],
            ['Prestasi',     'prestasi',     3],
            ['Kegiatan',     'kegiatan',     4],
        ];

        foreach ($items as [$nama, $slug, $urutan]) {
            $exists = $this->db->table('kategori_artikel')
                               ->getWhere(['slug' => $slug])
                               ->getRow();
            if (! $exists) {
                $this->db->table('kategori_artikel')->insert([
                    'nama'   => $nama,
                    'slug'   => $slug,
                    'urutan' => $urutan,
                ]);
            }
        }
    }

    // ────────────────────────────────────────────────────────────────
    private function seedKategoriGaleri(): void
    {
        $items = [
            ['Fasilitas',          'fasilitas',          1],
            ['Kegiatan Siswa',     'kegiatan-siswa',     2],
            ['Prestasi',           'prestasi',           3],
            ['Lingkungan Sekolah', 'lingkungan-sekolah', 4],
        ];

        foreach ($items as [$nama, $slug, $urutan]) {
            $exists = $this->db->table('kategori_galeri')
                               ->getWhere(['slug' => $slug])
                               ->getRow();
            if (! $exists) {
                $this->db->table('kategori_galeri')->insert([
                    'nama'   => $nama,
                    'slug'   => $slug,
                    'urutan' => $urutan,
                ]);
            }
        }
    }

    // ────────────────────────────────────────────────────────────────
    private function seedBidangGuru(): void
    {
        $items = [
            'Matematika & IPA',
            'Bahasa & Sastra',
            'IPS & Humaniora',
            'Seni & Olahraga',
            'Teknologi & Informatika',
            'Bimbingan & Konseling',
            'Tata Usaha & Administrasi',
        ];

        foreach ($items as $nama) {
            $exists = $this->db->table('bidang_guru')
                               ->getWhere(['nama' => $nama])
                               ->getRow();
            if (! $exists) {
                $this->db->table('bidang_guru')->insert(['nama' => $nama]);
            }
        }
    }
}
