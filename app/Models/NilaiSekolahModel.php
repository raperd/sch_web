<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiSekolahModel extends Model
{
    protected $table = 'nilai_sekolah';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'deskripsi', 'icon', 'urutan'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
