<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriGaleriModel extends Model
{
    protected $table         = 'kategori_galeri';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'slug', 'deskripsi', 'urutan'];
    protected $useTimestamps = true;
}
