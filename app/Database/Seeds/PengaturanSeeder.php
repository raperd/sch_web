<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // ── Grup: umum ───────────────────────────────────────────────
            ['site_name',           'SMA Negeri 1 Contoh',                                    'Nama Sekolah',                  'text',     'umum',      1],
            ['site_tagline',        'Unggul, Berkarakter, dan Berprestasi',                    'Tagline Sekolah',               'text',     'umum',      2],
            ['tahun_ajaran_aktif',  '2025/2026',                                               'Tahun Ajaran Aktif',            'text',     'umum',      3],
            ['akreditasi',          'A',                                                        'Akreditasi',                    'text',     'umum',      4],
            ['alamat',              'Jl. Contoh No. 1, Kota, Provinsi',                        'Alamat Sekolah',                'textarea', 'umum',      5],
            ['telepon',             '(021) 1234-5678',                                          'Nomor Telepon',                 'text',     'umum',      6],
            ['email',               'info@sman1contoh.sch.id',                                 'Email Sekolah',                 'text',     'umum',      7],
            ['logo_path',           null,                                                       'Logo Sekolah',                  'image',    'umum',      8],
            ['favicon_path',        null,                                                       'Favicon Website',               'image',    'umum',      9],

            // ── Grup: hero ───────────────────────────────────────────────
            ['hero_image_path',     null,                                                       'Foto Hero Beranda',             'image',    'hero',      1],
            ['hero_video_url',      null,                                                       'URL Video Hero (YouTube)',      'text',     'hero',      2],
            ['hero_judul',          'Selamat Datang di SMAN 1 Contoh',                         'Judul Hero',                    'text',     'hero',      3],
            ['hero_subjudul',       'Membentuk Generasi Unggul dan Berkarakter',               'Sub-judul Hero',                'text',     'hero',      4],

            // ── Grup: profil ─────────────────────────────────────────────
            ['nama_kepsek',         'Drs. Nama Kepala Sekolah, M.Pd.',                         'Nama Kepala Sekolah',           'text',     'profil',    1],
            ['nip_kepsek',          '19700101 199901 1 001',                                    'NIP Kepala Sekolah',            'text',     'profil',    2],
            ['foto_kepsek',         null,                                                       'Foto Kepala Sekolah',           'image',    'profil',    3],
            ['sambutan_kepsek',     'Selamat datang di website resmi sekolah kami. Kami berkomitmen untuk memberikan pendidikan terbaik bagi seluruh peserta didik.', 'Sambutan Kepala Sekolah', 'textarea', 'profil', 4],
            ['sejarah',             'Sekolah ini didirikan pada tahun ...',                    'Sejarah Sekolah',               'textarea', 'profil',    5],
            ['visi',                'Menjadi sekolah unggulan yang menghasilkan lulusan berakhlak mulia, berprestasi, dan berwawasan global.', 'Visi Sekolah', 'textarea', 'profil', 6],
            ['misi',                "1. Menyelenggarakan pembelajaran berkualitas\n2. Membina karakter peserta didik\n3. Mendorong prestasi akademik dan non-akademik", 'Misi Sekolah', 'textarea', 'profil', 7],

            // ── Grup: sosial ─────────────────────────────────────────────
            ['facebook_url',        null, 'URL Facebook',              'text',  'sosial', 1],
            ['instagram_url',       null, 'URL Instagram',             'text',  'sosial', 2],
            ['youtube_url',         null, 'URL YouTube',               'text',  'sosial', 3],
            ['tiktok_url',          null, 'URL TikTok',                'text',  'sosial', 4],
            ['osis_instagram_embed',null, 'Embed Instagram OSIS (URL)','text',  'sosial', 5],
            ['twitter_url',         null, 'URL Twitter / X',           'text',  'sosial', 6],
            ['whatsapp_url',        null, 'Nomor WhatsApp',            'text',  'sosial', 7],

            // ── Grup: statistik ──────────────────────────────────────────
            ['stat_tahun_berdiri',  '25+',    'Tahun Berdiri',       'text', 'statistik', 1],
            ['stat_siswa',          '1.000+', 'Jumlah Siswa',        'text', 'statistik', 2],
            ['stat_guru',           '60+',    'Tenaga Pendidik',     'text', 'statistik', 3],
            ['stat_ekskul',         '20+',    'Ekstrakurikuler',     'text', 'statistik', 4],
            ['stat_prestasi',       '50+',    'Prestasi Diraih',     'text', 'statistik', 5],

            // ── Grup: ppdb ───────────────────────────────────────────────
            ['ppdb_link_external',      '#',           'Link Portal PPDB (Pemda)',     'text',     'ppdb', 1],
            ['ppdb_tahun',              '2026/2027',   'Tahun PPDB',                  'text',     'ppdb', 2],
            ['ppdb_status',             '0',           'PPDB Sedang Buka',            'boolean',  'ppdb', 3],
            ['ppdb_deskripsi_footer',   'Informasi Penerimaan Peserta Didik Baru. Daftar melalui portal resmi Dinas Pendidikan.', 'Deskripsi SPMB di Footer', 'textarea', 'ppdb', 4],

            // ── Grup: tema ───────────────────────────────────────────────
            ['tema_primary',   '#1a5276', 'Warna Primer (Biru Utama)',    'color', 'tema', 1],
            ['tema_secondary', '#2e86c1', 'Warna Sekunder (Biru Sedang)', 'color', 'tema', 2],
            ['tema_accent',    '#d4ac0d', 'Warna Aksen (Gold)',           'color', 'tema', 3],
        ];

        foreach ($rows as [$key, $value, $label, $tipe, $grup, $urutan]) {
            $exists = $this->db->table('pengaturan')
                               ->getWhere(['setting_key' => $key])
                               ->getRow();
            if (! $exists) {
                $this->db->table('pengaturan')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value,
                    'label'         => $label,
                    'tipe'          => $tipe,
                    'grup'          => $grup,
                    'urutan'        => $urutan,
                ]);
            }
        }
    }
}
