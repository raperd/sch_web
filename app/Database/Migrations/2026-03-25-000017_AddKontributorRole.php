<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKontributorRole extends Migration
{
    public function up(): void
    {
        // Add 'kontributor' to the role ENUM and add email column if not present
        $this->db->query("ALTER TABLE `{$this->db->DBPrefix}users`
            MODIFY COLUMN `role` ENUM('superadmin', 'admin', 'kontributor') NOT NULL DEFAULT 'admin'");

        // Add email column for user management
        $fields = $this->db->getFieldNames($this->db->DBPrefix . 'users');
        if (!in_array('email', $fields)) {
            $this->forge->addColumn('users', [
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'nama',
                ],
            ]);
        }
    }

    public function down(): void
    {
        // Revert ENUM back to original values
        $this->db->query("ALTER TABLE `{$this->db->DBPrefix}users`
            MODIFY COLUMN `role` ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin'");

        $fields = $this->db->getFieldNames($this->db->DBPrefix . 'users');
        if (in_array('email', $fields)) {
            $this->forge->dropColumn('users', 'email');
        }
    }
}
