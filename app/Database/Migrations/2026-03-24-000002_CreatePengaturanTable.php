<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaturanTable extends Migration
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
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['text', 'textarea', 'image', 'boolean', 'json'],
                'default'    => 'text',
                'null'       => false,
            ],
            'grup' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'umum',
                'null'       => false,
            ],
            'urutan' => [
                'type'    => 'INT',
                'default' => 0,
                'null'    => false,
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
        $this->forge->addUniqueKey('setting_key');
        $this->forge->addKey('grup');

        $this->forge->createTable('pengaturan');
    }

    public function down(): void
    {
        $this->forge->dropTable('pengaturan', true);
    }
}
