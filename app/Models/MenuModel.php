<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table         = 'menu';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['parent_id', 'nama', 'url', 'icon', 'target', 'urutan', 'is_active', 'lokasi'];

    protected $useTimestamps = true;

    public function getPublicMenu(): array
    {
        return $this->where('lokasi', 'publik')
                    ->where('is_active', 1)
                    ->where('parent_id', null)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }

    public function getFooterMenu(): array
    {
        return $this->where('lokasi', 'footer')
                    ->where('is_active', 1)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}
