<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 7 menu utama publik
        $menuPublik = [
            ['nama' => 'Beranda',              'url' => '/',                'icon' => 'bi-house',           'urutan' => 1,  'lokasi' => 'publik'],
            ['nama' => 'Profil & Fasilitas',   'url' => '/profil',          'icon' => 'bi-building',        'urutan' => 2,  'lokasi' => 'publik'],
            ['nama' => 'Akademik',             'url' => '/akademik',        'icon' => 'bi-book',            'urutan' => 3,  'lokasi' => 'publik'],
            ['nama' => 'Kehidupan Siswa',      'url' => '/kehidupan-siswa', 'icon' => 'bi-people',          'urutan' => 4,  'lokasi' => 'publik'],
            ['nama' => 'Direktori Guru & Staf','url' => '/direktori',       'icon' => 'bi-person-badge',    'urutan' => 5,  'lokasi' => 'publik'],
            ['nama' => 'Berita & Artikel',     'url' => '/berita',          'icon' => 'bi-newspaper',       'urutan' => 6,  'lokasi' => 'publik'],
            ['nama' => 'SPMB',                 'url' => '/ppdb',            'icon' => 'bi-clipboard-check', 'urutan' => 7,  'lokasi' => 'publik'],
        ];

        foreach ($menuPublik as $item) {
            $this->db->table('menu')->insert([
                'parent_id' => null,
                'nama'      => $item['nama'],
                'url'       => $item['url'],
                'icon'      => $item['icon'],
                'target'    => '_self',
                'urutan'    => $item['urutan'],
                'is_active' => 1,
                'lokasi'    => $item['lokasi'],
            ]);
        }

        // Menu footer
        $menuFooter = [
            ['nama' => 'Beranda',          'url' => '/',                'urutan' => 1],
            ['nama' => 'Profil Sekolah',   'url' => '/profil',          'urutan' => 2],
            ['nama' => 'Berita & Artikel', 'url' => '/berita',          'urutan' => 3],
            ['nama' => 'SPMB',             'url' => '/ppdb',            'urutan' => 4],
            ['nama' => 'Link Terkait',     'url' => '/link-terkait',    'urutan' => 5],
        ];

        foreach ($menuFooter as $item) {
            $this->db->table('menu')->insert([
                'parent_id' => null,
                'nama'      => $item['nama'],
                'url'       => $item['url'],
                'icon'      => null,
                'target'    => '_self',
                'urutan'    => $item['urutan'],
                'is_active' => 1,
                'lokasi'    => 'footer',
            ]);
        }

        // Quick links untuk beranda
        $quickLinks = [
            ['label' => 'SPMB 2026/2027',   'url' => '/ppdb',            'icon' => 'bi-clipboard-check', 'warna' => 'primary',  'urutan' => 1],
            ['label' => 'Akademik',         'url' => '/akademik',        'icon' => 'bi-book-half',       'warna' => 'success',  'urutan' => 2],
            ['label' => 'Ekstrakurikuler',  'url' => '/kehidupan-siswa', 'icon' => 'bi-trophy',          'warna' => 'warning',  'urutan' => 3],
            ['label' => 'Direktori Guru',   'url' => '/direktori',       'icon' => 'bi-person-badge',    'warna' => 'info',     'urutan' => 4],
            ['label' => 'Galeri Sekolah',   'url' => '/profil#tab-galeri','icon' => 'bi-images',          'warna' => 'secondary','urutan' => 5],
            ['label' => 'Berita Terbaru',   'url' => '/berita',          'icon' => 'bi-newspaper',       'warna' => 'danger',   'urutan' => 6],
        ];

        $this->db->table('quick_links')->insertBatch(
            array_map(fn ($ql) => array_merge($ql, ['target' => '_self', 'is_active' => 1]), $quickLinks)
        );

        // Kategori artikel default
        $kategoriArtikel = [
            ['nama' => 'Berita',       'slug' => 'berita',       'urutan' => 1],
            ['nama' => 'Pengumuman',   'slug' => 'pengumuman',   'urutan' => 2],
            ['nama' => 'Prestasi',     'slug' => 'prestasi',     'urutan' => 3],
            ['nama' => 'Kegiatan',     'slug' => 'kegiatan',     'urutan' => 4],
        ];
        $this->db->table('kategori_artikel')->insertBatch($kategoriArtikel);

        // Kategori galeri default
        $kategoriGaleri = [
            ['nama' => 'Fasilitas',         'slug' => 'fasilitas',          'urutan' => 1],
            ['nama' => 'Kegiatan Siswa',    'slug' => 'kegiatan-siswa',     'urutan' => 2],
            ['nama' => 'Prestasi',          'slug' => 'prestasi',           'urutan' => 3],
            ['nama' => 'Lingkungan Sekolah','slug' => 'lingkungan-sekolah', 'urutan' => 4],
        ];
        $this->db->table('kategori_galeri')->insertBatch($kategoriGaleri);

        // Bidang guru default
        $bidangGuru = [
            ['nama' => 'Matematika & IPA'],
            ['nama' => 'Bahasa & Sastra'],
            ['nama' => 'IPS & Humaniora'],
            ['nama' => 'Seni & Olahraga'],
            ['nama' => 'Teknologi & Informatika'],
            ['nama' => 'Bimbingan & Konseling'],
            ['nama' => 'Tata Usaha & Administrasi'],
        ];
        $this->db->table('bidang_guru')->insertBatch($bidangGuru);
    }
}
