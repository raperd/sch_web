<?php

namespace App\Models;

use CodeIgniter\Model;

class BidangGuruModel extends Model
{
    protected $table         = 'bidang_guru';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama'];
    protected $useTimestamps = false;
}
