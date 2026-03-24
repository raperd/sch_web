<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriModel extends Model
{
    protected $table         = 'galeri';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'kategori_id', 'judul', 'deskripsi', 'file_path', 'tipe', 'thumbnail', 'is_featured', 'urutan',
    ];

    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    public function getFeatured(int $limit = 8): array
    {
        return $this->select('galeri.*, kategori_galeri.nama as kategori_nama')
                    ->join('kategori_galeri', 'kategori_galeri.id = galeri.kategori_id', 'left')
                    ->where('galeri.is_featured', 1)
                    ->orderBy('galeri.urutan', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getByKategori(int $kategoriId): array
    {
        return $this->where('kategori_id', $kategoriId)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}
