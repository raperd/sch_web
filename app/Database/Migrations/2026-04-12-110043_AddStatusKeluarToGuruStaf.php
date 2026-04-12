<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusKeluarToGuruStaf extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('guru_staf', [
            'status_keluar' => [
                'type'       => 'ENUM',
                'constraint' => ['purna_tugas', 'mutasi'],
                'null'       => true,
                'default'    => null,
                'after'      => 'tahun_keluar',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('guru_staf', 'status_keluar');
    }
}
