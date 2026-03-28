<?php

namespace App\Models;

use CodeIgniter\Model;

class AlbumFotoModel extends Model
{
    protected $table         = 'album_foto';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['judul', 'slug', 'deskripsi', 'cover_foto', 'link_google_foto', 'tanggal', 'urutan', 'is_published'];
    protected $useTimestamps = true;

    public function getPublished(): array
    {
        return $this->where('is_published', 1)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('tanggal', 'DESC')
                    ->findAll();
    }
}
