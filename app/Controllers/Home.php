<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $artikelModel   = new \App\Models\ArtikelModel();
        $quickLinkModel = new \App\Models\QuickLinkModel();
        $prestasiModel  = new \App\Models\PrestasiModel();

        return view('home/index', [
            'title'           => 'Beranda',
            'berita_terbaru'  => $artikelModel->getTerbaru(6),
            'berita_utama'    => $artikelModel->getFeatured(1),
            'quick_links'     => $quickLinkModel->getAktif(),
            'prestasi_unggulan' => $prestasiModel->getFeatured(6),
        ]);
    }
}
