<?php

namespace App\Models;

use CodeIgniter\Model;

class AplikasiModel extends Model
{
    protected $table = 'aplikasi_terkait';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'url', 'deskripsi', 'icon', 'urutan', 'is_active'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActive($limit = 0)
    {
        $this->where('is_active', 1)->orderBy('urutan', 'ASC');
        if ($limit > 0) {
            $this->limit($limit);
        }
        return $this->findAll();
    }
}
