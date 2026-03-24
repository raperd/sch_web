<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table         = 'kegiatan';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'judul', 'deskripsi', 'tanggal', 'tanggal_selesai', 'lokasi',
        'foto', 'tipe', 'status', 'is_featured',
    ];

    protected $useTimestamps = true;

    public function upcoming(int $limit = 5): array
    {
        return $this->where('tanggal >=', date('Y-m-d'))
                    ->whereIn('status', ['upcoming', 'ongoing'])
                    ->orderBy('tanggal', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getFeatured(int $limit = 3): array
    {
        return $this->where('is_featured', 1)
                    ->orderBy('tanggal', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
