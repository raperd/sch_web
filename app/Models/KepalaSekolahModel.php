<?php

namespace App\Models;

use CodeIgniter\Model;

class KepalaSekolahModel extends Model
{
    protected $table         = 'kepala_sekolah';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama', 'foto', 'periode_mulai', 'periode_selesai',
        'gelar_depan', 'gelar_belakang', 'keterangan', 'urutan',
    ];
    protected $useTimestamps = true;

    /** Nama lengkap dengan gelar */
    public function namaLengkap(array $row): string
    {
        $parts = array_filter([
            $row['gelar_depan'] ?? '',
            $row['nama'],
            $row['gelar_belakang'] ?? '',
        ]);
        return implode(' ', $parts);
    }

    /** Ambil semua, urut dari periode terlama */
    public function getAllOrdered(): array
    {
        return $this->orderBy('urutan', 'ASC')
                    ->orderBy('periode_mulai', 'ASC')
                    ->findAll();
    }

    /** Kepala sekolah yang sedang menjabat */
    public function getActive(): ?array
    {
        return $this->where('periode_selesai', null)
                    ->orderBy('periode_mulai', 'DESC')
                    ->first();
    }
}
