<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting — ikuti dependency tabel
        $this->call('UserSeeder');
        $this->call('PengaturanSeeder');
        $this->call('MenuSeeder');
    }
}
