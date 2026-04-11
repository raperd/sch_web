<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrestasiTable extends Migration
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
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kategori' => [
                'type'       => 'ENUM',
                'constraint' => ['akademik', 'non_akademik'],
                'default'    => 'akademik',
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['sekolah', 'kecamatan', 'kota_kabupaten', 'provinsi', 'nasional', 'internasional'],
                'default'    => 'sekolah',
            ],
            'tahun' => [
                'type'       => 'YEAR',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'nama_siswa' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pembimbing' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'urutan' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kategori');
        $this->forge->addKey('tahun');
        $this->forge->addKey('is_featured');
        $this->forge->createTable('prestasi');
    }

    public function down(): void
    {
        $this->forge->dropTable('prestasi', true);
    }
}
