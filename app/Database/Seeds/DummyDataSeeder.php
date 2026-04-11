<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DummyDataSeeder — data contoh untuk demo/testing.
 * JANGAN dijalankan di production yang sudah punya data nyata.
 *
 * Cara pakai: php spark db:seed DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedArtikel();
        $this->seedGuru();
        $this->seedKegiatan();
        $this->seedPrestasi();
        $this->seedEkskul();
        $this->seedPpdbKonten();
        $this->seedGaleri();
    }

    private function seedArtikel(): void
    {
        $kategori = $this->db->table('kategori_artikel')->get()->getResultArray();
        $userId   = $this->db->table('users')->getWhere(['role' => 'superadmin'])->getRow();
        if (!$userId || empty($kategori)) return;

        $articles = [
            ['Sekolah Raih Akreditasi A Tahun Ini', 'berita', 'Sekolah kami kembali meraih akreditasi A dari BAN-S/M. Hasil ini merupakan buah dari kerja keras seluruh warga sekolah.', 'published', 1],
            ['Jadwal Ujian Semester Ganjil 2026', 'pengumuman', 'Pelaksanaan Ujian Akhir Semester Ganjil akan dilaksanakan pada tanggal 15-25 Januari 2026. Semua siswa wajib hadir.', 'published', 0],
            ['Siswa Kami Juara Olimpiade Sains Nasional', 'prestasi', 'Tiga siswa kita berhasil meraih medali emas, perak, dan perunggu di ajang Olimpiade Sains Nasional tahun ini.', 'published', 1],
            ['Kegiatan Pengenalan Lingkungan Sekolah', 'kegiatan', 'MPLS (Masa Pengenalan Lingkungan Sekolah) telah dilaksanakan dengan meriah. Para siswa baru terlihat antusias mengikuti rangkaian kegiatan.', 'published', 0],
            ['Workshop Coding untuk Siswa SMA', 'kegiatan', 'Program pelatihan coding dasar menggunakan Python berhasil diikuti oleh 60 siswa dari berbagai kelas.', 'published', 0],
            ['Pengumuman SPMB Tahun Ajaran 2026/2027', 'pengumuman', 'Pendaftaran SPMB sudah dibuka. Calon peserta didik baru dapat mendaftar melalui portal resmi Dinas Pendidikan setempat.', 'published', 1],
        ];

        foreach ($articles as [$judul, $kategoriSlug, $konten, $status, $featured]) {
            $katId = current(array_filter($kategori, fn($k) => $k['slug'] === $kategoriSlug));
            $katId = $katId ? $katId['id'] : $kategori[0]['id'];

            $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower(str_replace(' ', '-', $judul)));
            $slug = trim($slug, '-');

            $existing = $this->db->table('artikel')->getWhere(['slug' => $slug])->getRow();
            if ($existing) continue;

            $this->db->table('artikel')->insert([
                'kategori_id'  => $katId,
                'user_id'      => $userId->id,
                'judul'        => $judul,
                'slug'         => $slug,
                'ringkasan'    => substr($konten, 0, 150) . '...',
                'konten'       => '<p>' . $konten . '</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                'status'       => $status,
                'is_featured'  => $featured,
                'view_count'   => rand(10, 500),
                'published_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
            ]);
        }
    }

    private function seedGuru(): void
    {
        $bidang = $this->db->table('bidang_guru')->get()->getResultArray();
        if (empty($bidang)) return;

        $gurus = [
            ['Drs. Ahmad Fauzi, M.Pd', 'Kepala Sekolah', 'guru',  'Matematika', 'S2 Pendidikan Matematika', 'Mendidik bukan hanya mengajar, tetapi membentuk karakter generasi penerus bangsa.', 1],
            ['Siti Rahayu, S.Pd',      'Guru Bahasa Indonesia', 'guru', 'Bahasa Indonesia', 'S1 Pendidikan Bahasa Indonesia', 'Bahasa adalah jendela dunia, mari kita buka selebar-lebarnya.', 1],
            ['Budi Santoso, S.T',      'Guru Informatika',      'guru', 'Informatika',      'S1 Ilmu Komputer', 'Teknologi adalah alat, kreativitaslah yang menjadi kuncinya.', 1],
            ['Dr. Rina Wati, M.Si',    'Guru Kimia',            'guru', 'Kimia',            'S3 Kimia', 'Ilmu kimia ada di sekitar kita, tugas kita adalah menemukan dan memahaminya.', 1],
            ['Hendra Kusuma, S.Pd',    'Guru Olahraga',         'guru', 'Pendidikan Jasmani', 'S1 Pendidikan Olahraga', 'Mens sana in corpore sano — jiwa sehat dalam tubuh yang sehat.', 1],
            ['Dewi Lestari, S.E',      'Kepala Tata Usaha',     'staf', null, 'S1 Ekonomi', null, 0],
            ['Agus Prasetyo',          'Staf Perpustakaan',     'staf', null, 'SMA/K', null, 0],
        ];

        foreach ($gurus as $i => [$nama, $jabatan, $tipe, $mapel, $pendidikan, $filosofi, $active]) {
            $bidangId = $bidang[array_rand($bidang)]['id'];
            $existing = $this->db->table('guru_staf')->getWhere(['nama' => $nama])->getRow();
            if ($existing) continue;

            $this->db->table('guru_staf')->insert([
                'bidang_id'          => $bidangId,
                'nama'               => $nama,
                'jabatan'            => $jabatan,
                'tipe'               => $tipe,
                'mata_pelajaran'     => $mapel,
                'pendidikan'         => $pendidikan,
                'filosofi_mengajar'  => $filosofi,
                'is_active'          => $active,
                'urutan'             => $i + 1,
            ]);
        }
    }

    private function seedKegiatan(): void
    {
        $kegiatan = [
            ['Upacara Hari Kemerdekaan', 'event',  'Upacara bendera dalam rangka HUT RI ke-80 diikuti seluruh warga sekolah.', date('Y') . '-08-17', 'Lapangan Upacara', 'selesai', 0],
            ['Lomba Cerdas Cermat IPA',  'lomba',  'Kompetisi cerdas cermat antar kelas untuk mata pelajaran IPA.', date('Y') . '-09-15', 'Aula Sekolah', 'selesai', 0],
            ['Bakti Sosial ke Panti Asuhan', 'sosial', 'Kegiatan bakti sosial berupa penyerahan donasi dan kunjungan ke panti asuhan setempat.', date('Y') . '-10-01', 'Panti Asuhan Harapan', 'selesai', 1],
            ['Pentas Seni Akhir Tahun',  'event',  'Penampilan seni dari seluruh siswa dalam rangka menutup tahun ajaran.', date('Y') . '-12-10', 'Aula Utama', 'upcoming', 1],
            ['Olimpiade Sains Sekolah',  'lomba',  'Seleksi internal untuk Olimpiade Sains Nasional.', date('Y') . '-11-20', 'Lab IPA', 'upcoming', 0],
        ];

        foreach ($kegiatan as [$judul, $tipe, $deskripsi, $tanggal, $lokasi, $status, $featured]) {
            $existing = $this->db->table('kegiatan')->getWhere(['judul' => $judul])->getRow();
            if ($existing) continue;
            $this->db->table('kegiatan')->insert([
                'judul'       => $judul,
                'tipe'        => $tipe,
                'deskripsi'   => $deskripsi,
                'tanggal'     => $tanggal,
                'lokasi'      => $lokasi,
                'status'      => $status,
                'is_featured' => $featured,
            ]);
        }
    }

    private function seedPrestasi(): void
    {
        $prestasi = [
            ['Juara 1 Olimpiade Matematika Provinsi', 'akademik', 'provinsi', date('Y'), 'Rizki Maulana', 'Siti Rahayu, S.Pd', 1],
            ['Juara 2 Lomba Debat Bahasa Inggris Nasional', 'akademik', 'nasional', date('Y'), 'Anisa Putri', 'Hendra Lim, S.Pd', 1],
            ['Juara 1 Futsal Se-Kota', 'non_akademik', 'kota_kabupaten', date('Y'), 'Tim Futsal SMAN 1', 'Hendra Kusuma, S.Pd', 1],
            ['Medali Perunggu OSN Kimia', 'akademik', 'nasional', date('Y') - 1, 'Budi Prasetyo', 'Dr. Rina Wati, M.Si', 1],
            ['Juara 3 Lomba Karya Ilmiah Remaja', 'akademik', 'provinsi', date('Y') - 1, 'Dewi Sartika, Rina Amalia', 'Budi Santoso, S.T', 0],
            ['Juara 1 Tari Tradisional Kecamatan', 'non_akademik', 'kecamatan', date('Y'), 'Tim Tari Sekolah', 'Ibu Wati', 0],
            ['Juara Harapan Olimpiade Astronomi Nasional', 'akademik', 'nasional', date('Y'), 'Ahmad Faris', 'Drs. Ahmad Fauzi, M.Pd', 0],
        ];

        foreach ($prestasi as $i => [$judul, $kategori, $tingkat, $tahun, $siswa, $pembimbing, $featured]) {
            $existing = $this->db->table('prestasi')->getWhere(['judul' => $judul])->getRow();
            if ($existing) continue;
            $this->db->table('prestasi')->insert([
                'judul'       => $judul,
                'kategori'    => $kategori,
                'tingkat'     => $tingkat,
                'tahun'       => $tahun,
                'nama_siswa'  => $siswa,
                'pembimbing'  => $pembimbing,
                'is_featured' => $featured,
                'urutan'      => $i + 1,
            ]);
        }
    }

    private function seedEkskul(): void
    {
        $ekskuls = [
            ['Paskibra',       'Pasukan Pengibar Bendera Sekolah yang bertugas pada upacara resmi.',        'bi-flag',    'Sabtu 07:00-10:00', 'Pak Hendra'],
            ['Basket',         'Tim basket putra dan putri dengan jadwal latihan rutin.',                    'bi-dribbble', 'Senin & Rabu 15:00-17:00', 'Pak Agus'],
            ['Paduan Suara',   'Kelompok paduan suara yang tampil di berbagai acara sekolah.',               'bi-music-note-beamed', 'Jumat 14:00-16:00', 'Ibu Dewi'],
            ['Robotika',       'Klub robotika untuk mengembangkan kemampuan STEM siswa.',                    'bi-robot', 'Sabtu 08:00-11:00', 'Budi Santoso, S.T'],
            ['PMR',            'Palang Merah Remaja, berlatih pertolongan pertama dan donor darah.',         'bi-heart-pulse', 'Rabu 14:00-16:00', 'Ibu Rina'],
            ['Jurnalistik',    'Klub jurnalistik yang mengelola majalah dinding dan media sekolah.',         'bi-pencil-square', 'Kamis 14:00-16:00', 'Pak Dani'],
        ];

        foreach ($ekskuls as $i => [$nama, $deskripsi, $icon, $jadwal, $pembina]) {
            $existing = $this->db->table('ekstrakurikuler')->getWhere(['nama' => $nama])->getRow();
            if ($existing) continue;
            $this->db->table('ekstrakurikuler')->insert([
                'nama'      => $nama,
                'deskripsi' => $deskripsi,
                'jadwal'    => $jadwal,
                'pembina'   => $pembina,
                'is_active' => 1,
                'urutan'    => $i + 1,
            ]);
        }
    }

    private function seedPpdbKonten(): void
    {
        $konten = [
            ['persyaratan', 'Persyaratan Umum', '<ul><li>Ijazah/SKHUN SMP/MTs atau sederajat</li><li>Kartu Keluarga (KK)</li><li>Akta Kelahiran</li><li>Pas foto 3×4 (4 lembar)</li><li>Surat keterangan sehat dari dokter</li></ul>', 1],
            ['jadwal', 'Jadwal Pendaftaran', '<p><strong>Gelombang 1:</strong> 1 - 15 Juni 2026</p><p><strong>Pengumuman:</strong> 18 Juni 2026</p><p><strong>Daftar Ulang:</strong> 19 - 23 Juni 2026</p>', 2],
            ['alur', 'Daftar Akun Online', '<p>Buat akun di portal SPMB Dinas Pendidikan menggunakan nomor peserta ujian dan NIK.</p>', 1],
            ['alur', 'Isi Formulir Pendaftaran', '<p>Lengkapi data diri, unggah dokumen yang diperlukan, dan pilih jalur pendaftaran.</p>', 2],
            ['alur', 'Cetak Bukti Pendaftaran', '<p>Setelah mengisi formulir, cetak bukti pendaftaran sebagai tanda bukti resmi.</p>', 3],
            ['faq', 'Apakah ada biaya pendaftaran?', '<p>Tidak. Pendaftaran SPMB tidak dipungut biaya apapun. Seluruh proses gratis.</p>', 1],
            ['faq', 'Berapa nilai minimal yang diperlukan?', '<p>Nilai akan diproses secara sistem berdasarkan zonasi dan prestasi. Tidak ada nilai minimum yang ditetapkan secara khusus.</p>', 2],
        ];

        foreach ($konten as $i => [$tipe, $judul, $isi, $urutan]) {
            $existing = $this->db->table('ppdb_konten')->getWhere(['judul_blok' => $judul])->getRow();
            if ($existing) continue;
            $this->db->table('ppdb_konten')->insert([
                'judul_blok' => $judul,
                'konten'     => $isi,
                'tipe'       => $tipe,
                'urutan'     => $urutan,
                'is_active'  => 1,
            ]);
        }
    }

    private function seedGaleri(): void
    {
        $kategori = $this->db->table('kategori_galeri')->get()->getResultArray();
        if (empty($kategori)) return;

        $items = [
            ['Ruang Laboratorium IPA',  'fasilitas',       'Laboratorium IPA yang dilengkapi peralatan modern.', 0],
            ['Perpustakaan Sekolah',    'fasilitas',       'Perpustakaan dengan koleksi lebih dari 5000 buku.',  1],
            ['Lapangan Olahraga',       'fasilitas',       'Lapangan multifungsi untuk basket, voli, dan upacara.', 0],
            ['Kegiatan Pentas Seni',    'kegiatan-siswa',  'Siswa menampilkan berbagai pertunjukan seni.', 1],
            ['Lomba Kebersihan Kelas',  'kegiatan-siswa',  'Kompetisi kebersihan antar kelas setiap semester.', 0],
        ];

        foreach ($items as [$judul, $katSlug, $deskripsi, $featured]) {
            $katId = current(array_filter($kategori, fn($k) => $k['slug'] === $katSlug));
            $katId = $katId ? $katId['id'] : $kategori[0]['id'];
            $existing = $this->db->table('galeri')->getWhere(['judul' => $judul])->getRow();
            if ($existing) continue;
            $this->db->table('galeri')->insert([
                'kategori_id' => $katId,
                'judul'       => $judul,
                'deskripsi'   => $deskripsi,
                'file_path'   => 'placeholder.jpg',
                'tipe'        => 'foto',
                'is_featured' => $featured,
                'urutan'      => 0,
            ]);
        }
    }
}
