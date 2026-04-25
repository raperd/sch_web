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

        // OG description: ringkasan jika ada, fallback ke 160 karakter awal konten
        $ogDesc = $artikel['ringkasan']
            ?: mb_strimwidth(strip_tags($artikel['konten']), 0, 160, '…');

        return view('berita/detail', [
            'title'            => $artikel['judul'],
            'artikel'          => $artikel,
            'terkait'          => $model->withRelations()->published()
                                        ->where('artikel.kategori_id', $artikel['kategori_id'])
                                        ->where('artikel.id !=', $artikel['id'])
                                        ->limit(3)->findAll(),
            // OpenGraph
            'meta_desc'        => $ogDesc,
            'og_type'          => 'article',
            'og_title'         => $artikel['judul'] . ' — ' . (setting('site_name') ?? 'Website Sekolah'),
            'og_desc'          => $ogDesc,
            'og_image'         => $artikel['thumbnail']
                                    ? base_url('uploads/artikel/' . $artikel['thumbnail'])
                                    : null,
            'og_article_time'  => $artikel['published_at'],
        ]);
    }
}
