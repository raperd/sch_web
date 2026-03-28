<?php

namespace App\Controllers;

use App\Models\FasilitasModel;
use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;
use App\Models\KepalaSekolahModel;
use App\Models\NilaiSekolahModel;

class Profil extends BaseController
{
    public function index(): string
    {
        $fasilitasModel      = new FasilitasModel();
        $galeriModel         = new GaleriModel();
        $kategoriGaleriModel = new KategoriGaleriModel();
        $kepsekModel         = new KepalaSekolahModel();
        $nilaiModel          = new NilaiSekolahModel();

        $kategoriFasilitas  = $kategoriGaleriModel->where('slug', 'fasilitas')->first();
        $kategoriLingkungan = $kategoriGaleriModel->where('slug', 'lingkungan-sekolah')->first();

        return view('profil/index', [
            'title'               => 'Profil & Fasilitas',
            'fasilitas'           => $fasilitasModel->orderBy('urutan', 'ASC')->findAll(),
            'galeri_fasilitas'    => $kategoriFasilitas
                                        ? $galeriModel->getByKategori($kategoriFasilitas['id'])
                                        : [],
            'galeri_lingkungan'   => $kategoriLingkungan
                                        ? $galeriModel->getByKategori($kategoriLingkungan['id'])
                                        : [],
            'galeri_unggulan'         => $galeriModel->getFeatured(8),
            'kepala_sekolah'          => $kepsekModel->getAllOrdered(),
            'nilai_sekolah'           => $nilaiModel->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }
}
