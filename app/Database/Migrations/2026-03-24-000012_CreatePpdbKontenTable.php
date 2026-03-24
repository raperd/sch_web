<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePpdbKontenTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul_blok' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'konten' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['persyaratan', 'jadwal', 'alur', 'faq', 'info'],
                'default'    => 'info',
                'null'       => false,
            ],
            'urutan' => [
                'type'    => 'INT',
                'default' => 0,
                'null'    => false,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('tipe');
        $this->forge->addKey('urutan');
        $this->forge->addKey('is_active');

        $this->forge->createTable('ppdb_konten');
    }

    public function down(): void
    {
        $this->forge->dropTable('ppdb_konten', true);
    }
}
