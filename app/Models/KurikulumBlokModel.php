<?php

namespace App\Models;

use CodeIgniter\Model;

class KurikulumBlokModel extends Model
{
    protected $table         = 'kurikulum_blok';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'judul', 'konten', 'urutan', 'is_active',
    ];

    public function getAktif(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
