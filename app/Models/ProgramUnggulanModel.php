<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramUnggulanModel extends Model
{
    protected $table         = 'program_unggulan';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'judul', 'deskripsi', 'icon', 'warna', 'urutan', 'is_active',
    ];

    /** Hanya yang aktif, diurutkan */
    public function getAktif(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
