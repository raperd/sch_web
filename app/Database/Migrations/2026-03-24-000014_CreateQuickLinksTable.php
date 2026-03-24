<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuickLinksTable extends Migration
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
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'warna' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'primary',
                'null'       => true,
            ],
            'target' => [
                'type'       => 'ENUM',
                'constraint' => ['_self', '_blank'],
                'default'    => '_self',
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
        $this->forge->addKey('urutan');
        $this->forge->addKey('is_active');

        $this->forge->createTable('quick_links');
    }

    public function down(): void
    {
        $this->forge->dropTable('quick_links', true);
    }
}
