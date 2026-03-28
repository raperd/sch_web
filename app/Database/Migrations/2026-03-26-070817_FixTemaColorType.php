<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixTemaColorType extends Migration
{
    public function up(): void
    {
        // Tambah 'color' ke ENUM tipe
        $this->db->query("ALTER TABLE `{$this->db->DBPrefix}pengaturan`
            MODIFY COLUMN `tipe` ENUM('text','textarea','image','boolean','json','color','richtext')
            NOT NULL DEFAULT 'text'");

        foreach (['tema_primary', 'tema_secondary', 'tema_accent'] as $key) {
            $this->db->table('pengaturan')
                ->where('setting_key', $key)
                ->update(['tipe' => 'color']);
        }
    }

    public function down(): void
    {
        foreach (['tema_primary', 'tema_secondary', 'tema_accent'] as $key) {
            $this->db->table('pengaturan')
                ->where('setting_key', $key)
                ->update(['tipe' => 'text']);
        }

        // Kembalikan ENUM tanpa 'color'
        $this->db->query("ALTER TABLE `{$this->db->DBPrefix}pengaturan`
            MODIFY COLUMN `tipe` ENUM('text','textarea','image','boolean','json')
            NOT NULL DEFAULT 'text'");
    }
}
