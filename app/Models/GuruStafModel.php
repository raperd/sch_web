<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruStafModel extends Model
{
    protected $table         = 'guru_staf';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'bidang_id', 'nip', 'nama', 'jabatan', 'tipe', 'mata_pelajaran',
        'pendidikan', 'foto', 'filosofi_mengajar', 'email_publik', 'is_active', 'urutan',
    ];

    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    public function aktif(): static
    {
        return $this->where('guru_staf.is_active', 1);
    }

    public function getByTipe(string $tipe): array
    {
        return $this->withBidang()->aktif()
                    ->where('guru_staf.tipe', $tipe)
                    ->orderBy('guru_staf.urutan', 'ASC')
                    ->findAll();
    }

    public function withBidang(): static
    {
        return $this->select('guru_staf.*, bidang_guru.nama as bidang_nama')
                    ->join('bidang_guru', 'bidang_guru.id = guru_staf.bidang_id', 'left');
    }
}
