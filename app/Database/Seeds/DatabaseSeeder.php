<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DatabaseSeeder — entry point tunggal untuk setup awal.
 *
 * Jalankan setelah `php spark migrate`:
 *   php spark db:seed DatabaseSeeder
 *
 * Aman dijalankan berkali-kali (idempoten):
 * setiap sub-seeder mengecek duplikat sebelum insert.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting — ikuti dependency tabel
        $this->call('UserSeeder');
        $this->call('PengaturanSeeder');
        $this->call('MenuSeeder');
        $this->call('DefaultNilaiSekolahSeeder');
        $this->call('AkademikSeeder');
    }
}
