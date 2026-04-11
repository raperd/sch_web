<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKepalaSekolahTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 150],
            'foto'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'periode_mulai'  => ['type' => 'YEAR'],
            'periode_selesai'=> ['type' => 'YEAR', 'null' => true, 'comment' => 'NULL = masih menjabat'],
            'gelar_depan'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'gelar_belakang' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'keterangan'     => ['type' => 'TEXT', 'null' => true],
            'urutan'         => ['type' => 'INT', 'default' => 0],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('kepala_sekolah');
    }

    public function down(): void
    {
        $this->forge->dropTable('kepala_sekolah', true);
    }
}
