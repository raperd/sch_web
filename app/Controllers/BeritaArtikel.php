<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriArtikelModel;

class BeritaArtikel extends BaseController
{
    public function index(): string
    {
        $model = new ArtikelModel();
        $perPage = 9;

        $data = [
            'title'   => 'Berita & Artikel',
            'artikel' => $model->withRelations()->published()->orderBy('artikel.published_at', 'DESC')->paginate($perPage, 'artikel'),
            'pager'   => $model->pager,
        ];

        return view('berita/index', $data);
    }

    public function detail(string $slug): string
    {
        $model   = new ArtikelModel();
        $artikel = $model->findBySlug($slug);

        if (! $artikel || $artikel['status'] !== 'published') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Artikel tidak ditemukan.');
        }

        $model->incrementView($artikel['id']);

        return view('berita/detail', [
            'title'   => $artikel['judul'],
            'artikel' => $artikel,
            'terkait' => $model->withRelations()->published()
                               ->where('artikel.kategori_id', $artikel['kategori_id'])
                               ->where('artikel.id !=', $artikel['id'])
                               ->limit(3)->findAll(),
        ]);
    }
}
