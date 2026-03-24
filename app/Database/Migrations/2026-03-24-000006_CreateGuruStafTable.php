<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuruStafTable extends Migration
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
            'bidang_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['guru', 'staf', 'tendik'],
                'default'    => 'guru',
                'null'       => false,
            ],
            'mata_pelajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pendidikan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'filosofi_mengajar' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'email_publik' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('tipe');
        $this->forge->addKey('is_active');
        $this->forge->addKey('urutan');

        $this->forge->createTable('guru_staf');

        $this->db->query('ALTER TABLE guru_staf ADD CONSTRAINT fk_guru_bidang FOREIGN KEY (bidang_id) REFERENCES bidang_guru(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE guru_staf DROP FOREIGN KEY fk_guru_bidang');
        $this->forge->dropTable('guru_staf', true);
    }
}
