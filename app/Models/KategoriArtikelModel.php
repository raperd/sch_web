<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriArtikelModel extends Model
{
    protected $table         = 'kategori_artikel';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'slug', 'deskripsi', 'urutan'];
    protected $useTimestamps = true;
}
