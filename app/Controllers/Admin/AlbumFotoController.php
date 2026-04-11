<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AlbumFotoModel;

class AlbumFotoController extends BaseController
{
    private AlbumFotoModel $model;

    public function __construct()
    {
        helper('app');
        $this->model = new AlbumFotoModel();
    }

    public function index(): string
    {
        return view('admin/album_foto/index', [
            'title'  => 'Album Foto',
            'albums' => $this->model->orderBy('urutan', 'ASC')->orderBy('tanggal', 'DESC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('admin/album_foto/form', [
            'title'       => 'Tambah Album Foto',
            'album'       => null,
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = [
            'judul'            => 'required|max_length[255]',
            'link_google_foto' => 'required|valid_url_strict|max_length[500]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slug  = $this->_uniqueSlug(slug_generate($this->request->getPost('judul')));
        $cover = $this->_saveCroppedCover() ?? $this->_uploadCover();

        $this->model->insert([
            'judul'            => $this->request->getPost('judul'),
            'slug'             => $slug,
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'cover_foto'       => $cover,
            'link_google_foto' => trim($this->request->getPost('link_google_foto')),
            'tanggal'          => $this->request->getPost('tanggal') ?: null,
            'urutan'           => (int) ($this->request->getPost('urutan') ?: 0),
            'is_published'     => (int) ($this->request->getPost('is_published') === '1'),
        ]);

        return redirect()->to(base_url('admin/album-foto'))->with('success', 'Album berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $album = $this->model->find($id);
        if (! $album) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Album tidak ditemukan.');
        }
        return view('admin/album_foto/form', [
            'title' => 'Edit Album Foto',
            'album' => $album,
        ]);
    }

    public function update(int $id)
    {
        $album = $this->model->find($id);
        if (! $album) {
            return redirect()->back()->with('error', 'Album tidak ditemukan.');
        }

        $rules = [
            'judul'            => 'required|max_length[255]',
            'link_google_foto' => 'required|valid_url_strict|max_length[500]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cover = $this->_saveCroppedCover() ?? $this->_uploadCover() ?? $album['cover_foto'];

        $this->model->update($id, [
            'judul'            => $this->request->getPost('judul'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'cover_foto'       => $cover,
            'link_google_foto' => trim($this->request->getPost('link_google_foto')),
            'tanggal'          => $this->request->getPost('tanggal') ?: null,
            'urutan'           => (int) ($this->request->getPost('urutan') ?: 0),
            'is_published'     => (int) ($this->request->getPost('is_published') === '1'),
        ]);

        return redirect()->to(base_url('admin/album-foto'))->with('success', 'Album berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $album = $this->model->find($id);
        if (! $album) {
            return redirect()->back()->with('error', 'Album tidak ditemukan.');
        }
        if (! empty($album['cover_foto'])) {
            $path = FCPATH . 'uploads/album_foto/' . $album['cover_foto'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $this->model->delete($id);
        return redirect()->to(base_url('admin/album-foto'))->with('success', 'Album berhasil dihapus.');
    }

    // -----------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------

    private function _saveCroppedCover(): ?string
    {
        $b64 = $this->request->getPost('cover_cropped');
        if (empty($b64) || ! str_contains($b64, 'base64,')) {
            return null;
        }

        [, $data] = explode('base64,', $b64);
        $img = imagecreatefromstring(base64_decode($data));
        if (! $img) {
            return null;
        }

        $dir = FCPATH . 'uploads/album_foto/';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = 'album_' . uniqid() . '.jpg';
        $w   = imagesx($img);
        $h   = imagesy($img);
        $out = imagecreatetruecolor(800, 450);
        imagecopyresampled($out, $img, 0, 0, 0, 0, 800, 450, $w, $h);
        imagejpeg($out, $dir . $filename, 85);
        imagedestroy($img);
        imagedestroy($out);
        return $filename;
    }

    private function _uploadCover(): ?string
    {
        $file = $this->request->getFile('cover_foto');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }
        $uploader = new \App\Libraries\ImageUpload();
        return $uploader->upload('cover_foto', 'album_foto') ?: null;
    }

    private function _uniqueSlug(string $base): string
    {
        $slug = $base;
        $i    = 1;
        while ($this->model->where('slug', $slug)->countAllResults(true) > 0) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
