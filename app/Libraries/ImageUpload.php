<?php

namespace App\Libraries;

class ImageUpload
{
    protected string $uploadPath = WRITEPATH . 'uploads/';
    protected array  $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    protected int    $maxSize = 2048; // KB

    /**
     * Upload gambar ke direktori tertentu.
     *
     * @param string $fieldName  Nama field input file HTML
     * @param string $module     Sub-direktori (artikel, guru, galeri, ppdb, pengaturan)
     * @return string|null       Nama file tersimpan, atau null jika gagal
     */
    public function upload(string $fieldName, string $module = 'umum'): ?string
    {
        $file = service('request')->getFile($fieldName);

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $ext = strtolower($file->getClientExtension());
        if (! in_array($ext, $this->allowedTypes, true)) {
            throw new \RuntimeException('Tipe file tidak diizinkan. Gunakan: ' . implode(', ', $this->allowedTypes));
        }

        if ($file->getSizeByUnit('kb') > $this->maxSize) {
            throw new \RuntimeException('Ukuran file melebihi batas ' . $this->maxSize . ' KB.');
        }

        $dir = $this->uploadPath . $module . '/';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $newName = uniqid($module . '_', true) . '.' . $ext;
        $file->move($dir, $newName);

        return $newName;
    }

    /**
     * Hapus file lama jika ada.
     */
    public function delete(string $module, string $filename): void
    {
        $path = $this->uploadPath . $module . '/' . $filename;

        if ($filename && file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Kembalikan URL publik untuk file yang diupload.
     */
    public function url(string $module, ?string $filename, string $placeholder = ''): string
    {
        if (! $filename) {
            return $placeholder;
        }

        return base_url('uploads/' . $module . '/' . $filename);
    }
}
