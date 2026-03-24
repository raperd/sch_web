<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenuTable extends Migration
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
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
            'nama' => [
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
            'lokasi' => [
                'type'       => 'ENUM',
                'constraint' => ['publik', 'footer', 'admin'],
                'default'    => 'publik',
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
        $this->forge->addKey('parent_id');
        $this->forge->addKey('lokasi');
        $this->forge->addKey('urutan');
        $this->forge->addKey('is_active');

        $this->forge->createTable('menu');

        $this->db->query('ALTER TABLE menu ADD CONSTRAINT fk_menu_parent FOREIGN KEY (parent_id) REFERENCES menu(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE menu DROP FOREIGN KEY fk_menu_parent');
        $this->forge->dropTable('menu', true);
    }
}
