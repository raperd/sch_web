<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestasiModel extends Model
{
    protected $table         = 'prestasi';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'judul', 'kategori', 'tingkat', 'tahun',
        'deskripsi', 'nama_siswa', 'pembimbing',
        'foto', 'is_featured', 'urutan',
    ];
    protected $useTimestamps = true;

    public function getFeatured(int $limit = 6): array
    {
        return $this->where('is_featured', 1)
                    ->orderBy('tahun', 'DESC')
                    ->orderBy('urutan', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getByKategori(string $kategori, int $limit = 20): array
    {
        return $this->where('kategori', $kategori)
                    ->orderBy('tahun', 'DESC')
                    ->orderBy('urutan', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getTerbaru(int $limit = 8): array
    {
        return $this->orderBy('tahun', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
