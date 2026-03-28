<?php

namespace App\Controllers;

use App\Models\KegiatanModel;
use App\Models\EkstrakurikulerModel;
use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;

class KehidupanSiswa extends BaseController
{
    public function index(): string
    {
        $kegiatanModel       = new KegiatanModel();
        $ekskulModel         = new EkstrakurikulerModel();
        $galeriModel         = new GaleriModel();
        $kategoriGaleriModel = new KategoriGaleriModel();

        $katKegiatan = $kategoriGaleriModel->where('slug', 'kegiatan-siswa')->first();

        return view('kehidupan_siswa/index', [
            'title'                    => 'Kehidupan Siswa & OSIS',
            'kegiatan'                 => $kegiatanModel->orderBy('tanggal', 'DESC')->limit(12)->findAll(),
            'upcoming'                 => $kegiatanModel->upcoming(5),
            'ekskul'                   => $ekskulModel->where('is_active', 1)->orderBy('urutan', 'ASC')->findAll(),
            'galeri_kegiatan'          => $katKegiatan ? $galeriModel->getByKategori($katKegiatan['id']) : [],
        ]);
    }
}
