<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTautanAplikasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 255],
            'url' => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'urutan' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('aplikasi_terkait');
    }

    public function down()
    {
        $this->forge->dropTable('aplikasi_terkait');
    }
}
