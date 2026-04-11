<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultNilaiSekolahSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('nilai_sekolah')->insertBatch([
            [
                'nama' => 'Keunggulan',
                'deskripsi' => 'Mendorong setiap siswa mencapai potensi terbaik di bidang akademik dan non-akademik.',
                'icon' => 'bi-award',
                'urutan' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Karakter',
                'deskripsi' => 'Membentuk generasi berkarakter, berakhlak mulia, dan berjiwa Pancasila.',
                'icon' => 'bi-heart',
                'urutan' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Inovasi',
                'deskripsi' => 'Mempersiapkan siswa menjadi warga global yang kreatif, inovatif, dan adaptif.',
                'icon' => 'bi-globe',
                'urutan' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
