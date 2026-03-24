<?php

namespace App\Models;

use CodeIgniter\Model;

class EkstrakurikulerModel extends Model
{
    protected $table         = 'ekstrakurikuler';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'deskripsi', 'foto', 'jadwal', 'pembina', 'prestasi', 'is_active', 'urutan'];
    protected $useTimestamps = true;
}
