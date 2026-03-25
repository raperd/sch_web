<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAkademikTable extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // program_unggulan — kartu program di halaman akademik
        // ---------------------------------------------------------------
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'judul'     => ['type' => 'VARCHAR', 'constraint' => 150],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'icon'      => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => 'bi-star'],
            'warna'     => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'primary'],
            'urutan'    => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'=> ['type' => 'DATETIME', 'null' => true],
            'updated_at'=> ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('program_unggulan');

        // ---------------------------------------------------------------
        // kurikulum_blok — accordion kurikulum
        // ---------------------------------------------------------------
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'judul'     => ['type' => 'VARCHAR', 'constraint' => 150],
            'konten'    => ['type' => 'TEXT', 'null' => true],
            'urutan'    => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'=> ['type' => 'DATETIME', 'null' => true],
            'updated_at'=> ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('kurikulum_blok');
    }

    public function down(): void
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->forge->dropTable('program_unggulan', true);
        $this->forge->dropTable('kurikulum_blok', true);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
