<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultNilaiSekolahSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Keunggulan', 'Mendorong setiap siswa mencapai potensi terbaik di bidang akademik dan non-akademik.', 'bi-award',      1],
            ['Karakter',   'Membentuk generasi berkarakter, berakhlak mulia, dan berjiwa Pancasila.',              'bi-heart',      2],
            ['Inovasi',    'Mempersiapkan siswa menjadi warga global yang kreatif, inovatif, dan adaptif.',        'bi-globe',      3],
        ];

        foreach ($items as [$nama, $deskripsi, $icon, $urutan]) {
            $exists = $this->db->table('nilai_sekolah')
                               ->getWhere(['nama' => $nama])
                               ->getRow();
            if (! $exists) {
                $this->db->table('nilai_sekolah')->insert([
                    'nama'       => $nama,
                    'deskripsi'  => $deskripsi,
                    'icon'       => $icon,
                    'urutan'     => $urutan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
