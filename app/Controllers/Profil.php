<?php

namespace App\Controllers;

use App\Models\FasilitasModel;
use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;

class Profil extends BaseController
{
    public function index(): string
    {
        $fasilitasModel     = new FasilitasModel();
        $galeriModel        = new GaleriModel();
        $kategoriGaleriModel = new KategoriGaleriModel();

        $kategoriFasilitas = $kategoriGaleriModel->where('slug', 'fasilitas')->first();

        return view('profil/index', [
            'title'            => 'Profil & Fasilitas',
            'fasilitas'        => $fasilitasModel->orderBy('urutan', 'ASC')->findAll(),
            'galeri_fasilitas' => $kategoriFasilitas
                                    ? $galeriModel->getByKategori($kategoriFasilitas['id'])
                                    : [],
            'galeri_unggulan'  => $galeriModel->getFeatured(8),
        ]);
    }
}
