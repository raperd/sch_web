<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Grup: umum
            ['setting_key' => 'site_name',        'setting_value' => 'SMA Negeri 1 Contoh',                'label' => 'Nama Sekolah',            'tipe' => 'text',     'grup' => 'umum',   'urutan' => 1],
            ['setting_key' => 'site_tagline',      'setting_value' => 'Unggul, Berkarakter, dan Berprestasi','label' => 'Tagline Sekolah',          'tipe' => 'text',     'grup' => 'umum',   'urutan' => 2],
            ['setting_key' => 'tahun_ajaran_aktif','setting_value' => '2025/2026',                          'label' => 'Tahun Ajaran Aktif',       'tipe' => 'text',     'grup' => 'umum',   'urutan' => 3],
            ['setting_key' => 'akreditasi',        'setting_value' => 'A',                                  'label' => 'Akreditasi',               'tipe' => 'text',     'grup' => 'umum',   'urutan' => 4],
            ['setting_key' => 'alamat',            'setting_value' => 'Jl. Contoh No. 1, Kota, Provinsi',  'label' => 'Alamat Sekolah',           'tipe' => 'textarea', 'grup' => 'umum',   'urutan' => 5],
            ['setting_key' => 'telepon',           'setting_value' => '(021) 1234-5678',                    'label' => 'Nomor Telepon',            'tipe' => 'text',     'grup' => 'umum',   'urutan' => 6],
            ['setting_key' => 'email',             'setting_value' => 'info@sman1contoh.sch.id',            'label' => 'Email Sekolah',            'tipe' => 'text',     'grup' => 'umum',   'urutan' => 7],
            ['setting_key' => 'logo_path',         'setting_value' => null,                                 'label' => 'Logo Sekolah',             'tipe' => 'image',    'grup' => 'umum',   'urutan' => 8],

            // Grup: hero
            ['setting_key' => 'hero_image_path',   'setting_value' => null,                                 'label' => 'Foto Hero Beranda',        'tipe' => 'image',    'grup' => 'hero',   'urutan' => 1],
            ['setting_key' => 'hero_video_url',    'setting_value' => null,                                 'label' => 'URL Video Hero (YouTube)', 'tipe' => 'text',     'grup' => 'hero',   'urutan' => 2],
            ['setting_key' => 'hero_judul',        'setting_value' => 'Selamat Datang di SMAN 1 Contoh',   'label' => 'Judul Hero',               'tipe' => 'text',     'grup' => 'hero',   'urutan' => 3],
            ['setting_key' => 'hero_subjudul',     'setting_value' => 'Membentuk Generasi Unggul dan Berkarakter','label' => 'Sub-judul Hero',    'tipe' => 'text',     'grup' => 'hero',   'urutan' => 4],

            // Grup: profil
            ['setting_key' => 'nama_kepsek',       'setting_value' => 'Drs. Nama Kepala Sekolah, M.Pd.',   'label' => 'Nama Kepala Sekolah',      'tipe' => 'text',     'grup' => 'profil', 'urutan' => 1],
            ['setting_key' => 'nip_kepsek',        'setting_value' => '19700101 199901 1 001',              'label' => 'NIP Kepala Sekolah',       'tipe' => 'text',     'grup' => 'profil', 'urutan' => 2],
            ['setting_key' => 'foto_kepsek',       'setting_value' => null,                                 'label' => 'Foto Kepala Sekolah',      'tipe' => 'image',    'grup' => 'profil', 'urutan' => 3],
            ['setting_key' => 'sambutan_kepsek',   'setting_value' => 'Selamat datang di website resmi sekolah kami. Kami berkomitmen untuk memberikan pendidikan terbaik bagi seluruh peserta didik.', 'label' => 'Sambutan Kepala Sekolah', 'tipe' => 'textarea', 'grup' => 'profil', 'urutan' => 4],
            ['setting_key' => 'sejarah',           'setting_value' => 'Sekolah ini didirikan pada tahun ...',                                      'label' => 'Sejarah Sekolah',          'tipe' => 'textarea', 'grup' => 'profil', 'urutan' => 5],
            ['setting_key' => 'visi',              'setting_value' => 'Menjadi sekolah unggulan yang menghasilkan lulusan berakhlak mulia, berprestasi, dan berwawasan global.',              'label' => 'Visi Sekolah',             'tipe' => 'textarea', 'grup' => 'profil', 'urutan' => 6],
            ['setting_key' => 'misi',              'setting_value' => "1. Menyelenggarakan pembelajaran berkualitas\n2. Membina karakter peserta didik\n3. Mendorong prestasi akademik dan non-akademik", 'label' => 'Misi Sekolah', 'tipe' => 'textarea', 'grup' => 'profil', 'urutan' => 7],

            // Grup: sosial
            ['setting_key' => 'facebook_url',      'setting_value' => null,                                 'label' => 'URL Facebook',             'tipe' => 'text',     'grup' => 'sosial', 'urutan' => 1],
            ['setting_key' => 'instagram_url',     'setting_value' => null,                                 'label' => 'URL Instagram',            'tipe' => 'text',     'grup' => 'sosial', 'urutan' => 2],
            ['setting_key' => 'youtube_url',       'setting_value' => null,                                 'label' => 'URL YouTube',              'tipe' => 'text',     'grup' => 'sosial', 'urutan' => 3],
            ['setting_key' => 'tiktok_url',        'setting_value' => null,                                 'label' => 'URL TikTok',               'tipe' => 'text',     'grup' => 'sosial', 'urutan' => 4],
            ['setting_key' => 'osis_instagram_embed', 'setting_value' => null,                              'label' => 'Embed Instagram OSIS (URL)','tipe' => 'text',    'grup' => 'sosial', 'urutan' => 5],

            // Grup: ppdb
            ['setting_key' => 'ppdb_link_external','setting_value' => '#',                                  'label' => 'Link Portal PPDB (Pemda)',  'tipe' => 'text',     'grup' => 'ppdb',   'urutan' => 1],
            ['setting_key' => 'ppdb_tahun',        'setting_value' => '2026/2027',                          'label' => 'Tahun PPDB',               'tipe' => 'text',     'grup' => 'ppdb',   'urutan' => 2],
            ['setting_key' => 'ppdb_status',       'setting_value' => '0',                                  'label' => 'PPDB Sedang Buka',         'tipe' => 'boolean',  'grup' => 'ppdb',   'urutan' => 3],
        ];

        $this->db->table('pengaturan')->insertBatch($data);
    }
}
