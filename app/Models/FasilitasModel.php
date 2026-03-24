<?php

namespace App\Models;

use CodeIgniter\Model;

class FasilitasModel extends Model
{
    protected $table         = 'fasilitas';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'deskripsi', 'icon', 'foto', 'jumlah', 'kondisi', 'urutan'];
    protected $useTimestamps = true;
}
