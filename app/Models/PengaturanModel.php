<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table         = 'pengaturan';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['setting_key', 'setting_value', 'label', 'tipe', 'grup', 'urutan'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil nilai pengaturan berdasarkan key.
     */
    public function getByKey(string $key, mixed $default = null): mixed
    {
        $row = $this->where('setting_key', $key)->first();

        return $row ? $row['setting_value'] : $default;
    }

    /**
     * Simpan / update nilai pengaturan berdasarkan key.
     */
    public function setByKey(string $key, mixed $value): bool
    {
        $row = $this->where('setting_key', $key)->first();

        if ($row) {
            return $this->update($row['id'], ['setting_value' => $value]);
        }

        return (bool) $this->insert(['setting_key' => $key, 'setting_value' => $value, 'label' => $key]);
    }

    /**
     * Ambil semua pengaturan, di-group berdasarkan 'grup'.
     */
    public function getAllGrouped(): array
    {
        $rows    = $this->orderBy('urutan', 'ASC')->findAll();
        $grouped = [];

        foreach ($rows as $row) {
            $grouped[$row['grup']][$row['setting_key']] = $row;
        }

        return $grouped;
    }

    /**
     * Ambil semua pengaturan sebagai key => value flat array.
     */
    public function getAllFlat(): array
    {
        $rows   = $this->findAll();
        $result = [];

        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }

        return $result;
    }
}
