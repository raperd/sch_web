<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProgramUnggulan();
        $this->seedKurikulumBlok();
    }

    private function seedProgramUnggulan(): void
    {
        $programs = [
            ['Literasi Digital',        'Pembekalan keterampilan teknologi informasi dan komunikasi untuk generasi siap era digital.',         'bi-laptop',      'primary',   1],
            ['Bahasa Inggris Intensif', 'Program penguatan bahasa Inggris dengan pendekatan komunikatif dan interaktif setiap hari.',          'bi-globe2',      'success',   2],
            ['Olimpiade Sains',         'Pembinaan intensif siswa berbakat untuk kompetisi sains tingkat kabupaten, provinsi, dan nasional.',   'bi-trophy',      'warning',   3],
            ['Pendidikan Karakter',     'Pembiasaan nilai-nilai Pancasila, keagamaan, dan budaya lokal yang terintegrasi dalam keseharian.',    'bi-heart-pulse', 'danger',    4],
            ['Seni & Kreativitas',      'Pengembangan bakat seni rupa, musik, dan pertunjukan melalui kelas dan festival tahunan.',             'bi-easel',       'info',      5],
            ['Kepemimpinan Siswa',      'Pelatihan kepemimpinan melalui OSIS, MPK, dan program pembinaan calon pemimpin muda.',                 'bi-people',      'secondary', 6],
        ];

        foreach ($programs as [$judul, $deskripsi, $icon, $warna, $urutan]) {
            if ($this->db->table('program_unggulan')->getWhere(['judul' => $judul])->getRow()) continue;
            $this->db->table('program_unggulan')->insert(compact('judul', 'deskripsi', 'icon', 'warna', 'urutan') + ['is_active' => 1]);
        }
    }

    private function seedKurikulumBlok(): void
    {
        $bloks = [
            [
                'judul'  => 'Mata Pelajaran Inti',
                'konten' => '<p>Mata pelajaran inti yang wajib ditempuh oleh seluruh siswa:</p><ul><li>Pendidikan Agama &amp; Budi Pekerti</li><li>Pendidikan Pancasila</li><li>Bahasa Indonesia</li><li>Matematika</li><li>IPA &amp; IPS (Terpadu)</li><li>Bahasa Inggris</li><li>Seni Budaya</li><li>Pendidikan Jasmani, Olahraga &amp; Kesehatan</li></ul>',
                'urutan' => 1,
            ],
            [
                'judul'  => 'Projek Penguatan Profil Pelajar Pancasila (P5)',
                'konten' => '<p>P5 adalah pembelajaran lintas disiplin ilmu untuk mengamati dan memikirkan solusi terhadap permasalahan di lingkungan sekitar. Tema yang diimplementasikan:</p><ul><li>Gaya Hidup Berkelanjutan</li><li>Kearifan Lokal</li><li>Bhinneka Tunggal Ika</li><li>Bangunlah Jiwa &amp; Raganya</li><li>Rekayasa &amp; Teknologi</li><li>Kewirausahaan</li></ul>',
                'urutan' => 2,
            ],
            [
                'judul'  => 'Jadwal & Beban Belajar',
                'konten' => '<table class="table table-bordered table-sm"><thead class="table-primary"><tr><th>Hari</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Keterangan</th></tr></thead><tbody><tr><td>Senin</td><td>07.00</td><td>14.30</td><td>Upacara Bendera</td></tr><tr><td>Selasa–Kamis</td><td>07.00</td><td>14.30</td><td>KBM Reguler</td></tr><tr><td>Jumat</td><td>07.00</td><td>11.30</td><td>Jumat Bersih</td></tr><tr><td>Sabtu</td><td>07.00</td><td>12.30</td><td>Ekstrakurikuler</td></tr></tbody></table>',
                'urutan' => 3,
            ],
            [
                'judul'  => 'Penilaian & Evaluasi',
                'konten' => '<p>Sistem penilaian Kurikulum Merdeka bersifat holistik:</p><ul><li><strong>Formatif</strong> — Tugas harian, presentasi, portofolio.</li><li><strong>Sumatif</strong> — Tes akhir semester dan akhir tahun.</li><li><strong>P5</strong> — Laporan profil perkembangan Profil Pelajar Pancasila.</li><li><strong>Rapor Naratif</strong> — Gambaran menyeluruh perkembangan belajar siswa.</li></ul>',
                'urutan' => 4,
            ],
        ];

        foreach ($bloks as $b) {
            if ($this->db->table('kurikulum_blok')->getWhere(['judul' => $b['judul']])->getRow()) continue;
            $this->db->table('kurikulum_blok')->insert($b + ['is_active' => 1]);
        }
    }
}
