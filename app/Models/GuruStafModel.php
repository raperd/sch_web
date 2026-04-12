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
        'pendidikan', 'foto', 'filosofi_mengajar', 'email_publik',
        'is_active', 'urutan', 'tahun_masuk', 'tahun_keluar', 'status_keluar',
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

    public function getAlumni(): array
    {
        return $this->withBidang()
                    ->where('guru_staf.is_active', 0)
                    ->orderBy('FIELD(guru_staf.status_keluar, "purna_tugas", "mutasi", NULL)', '', false)
                    ->orderBy('guru_staf.tahun_keluar', 'DESC')
                    ->orderBy('guru_staf.nama', 'ASC')
                    ->findAll();
    }

    public function withBidang(): static
    {
        return $this->select('guru_staf.*, bidang_guru.nama as bidang_nama')
                    ->join('bidang_guru', 'bidang_guru.id = guru_staf.bidang_id', 'left');
    }
}
