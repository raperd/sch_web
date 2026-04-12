<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTahunToGuruStaf extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('guru_staf', [
            'tahun_masuk' => [
                'type'    => 'SMALLINT',
                'unsigned' => true,
                'null'    => true,
                'default' => null,
                'after'   => 'is_active',
            ],
            'tahun_keluar' => [
                'type'    => 'SMALLINT',
                'unsigned' => true,
                'null'    => true,
                'default' => null,
                'after'   => 'tahun_masuk',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('guru_staf', ['tahun_masuk', 'tahun_keluar']);
    }
}
