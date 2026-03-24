<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateArtikelTable extends Migration
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
            'kategori_id' => [
                'type'     => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null'     => false,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 280,
                'null'       => false,
            ],
            'ringkasan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'konten' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'thumbnail' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published', 'archived'],
                'default'    => 'draft',
                'null'       => false,
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'view_count' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
            ],
            'tags' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'published_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
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
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('status');
        $this->forge->addKey('is_featured');
        $this->forge->addKey('published_at');
        $this->forge->addKey('kategori_id');

        $this->forge->createTable('artikel');

        // Foreign keys
        $this->db->query('ALTER TABLE artikel ADD CONSTRAINT fk_artikel_kategori FOREIGN KEY (kategori_id) REFERENCES kategori_artikel(id) ON DELETE RESTRICT');
        $this->db->query('ALTER TABLE artikel ADD CONSTRAINT fk_artikel_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE artikel DROP FOREIGN KEY fk_artikel_kategori');
        $this->db->query('ALTER TABLE artikel DROP FOREIGN KEY fk_artikel_user');
        $this->forge->dropTable('artikel', true);
    }
}
