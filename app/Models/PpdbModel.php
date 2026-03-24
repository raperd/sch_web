<?php

namespace App\Models;

use CodeIgniter\Model;

class PpdbModel extends Model
{
    protected $table         = 'ppdb_konten';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['judul_blok', 'konten', 'tipe', 'urutan', 'is_active'];
    protected $useTimestamps = true;

    public function getByTipe(string $tipe): array
    {
        return $this->where('tipe', $tipe)
                    ->where('is_active', 1)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}
