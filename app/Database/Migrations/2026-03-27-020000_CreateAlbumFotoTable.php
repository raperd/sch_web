<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlbumFotoTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'judul'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'deskripsi'        => ['type' => 'TEXT', 'null' => true],
            'cover_foto'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'link_google_foto' => ['type' => 'VARCHAR', 'constraint' => 500],
            'tanggal'          => ['type' => 'DATE', 'null' => true],
            'urutan'           => ['type' => 'INT', 'default' => 0],
            'is_published'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'       => ['type' => 'TIMESTAMP', 'null' => false, 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'updated_at'       => ['type' => 'TIMESTAMP', 'null' => false, 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('album_foto');
    }

    public function down(): void
    {
        $this->forge->dropTable('album_foto', true);
    }
}
