<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'username'  => 'superadmin',
                'password'  => password_hash('Admin@123!', PASSWORD_BCRYPT),
                'nama'      => 'Super Administrator',
                'role'      => 'superadmin',
                'is_active' => 1,
            ],
            [
                'username'  => 'admin',
                'password'  => password_hash('Admin@456!', PASSWORD_BCRYPT),
                'nama'      => 'Administrator',
                'role'      => 'admin',
                'is_active' => 1,
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
