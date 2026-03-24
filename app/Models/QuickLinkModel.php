<?php

namespace App\Models;

use CodeIgniter\Model;

class QuickLinkModel extends Model
{
    protected $table         = 'quick_links';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['label', 'url', 'icon', 'warna', 'target', 'urutan', 'is_active'];

    protected $useTimestamps = true;

    public function getAktif(): array
    {
        return $this->where('is_active', 1)->orderBy('urutan', 'ASC')->findAll();
    }
}
