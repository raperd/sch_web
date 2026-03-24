<?php

namespace App\Controllers;

use App\Models\KegiatanModel;
use App\Models\EkstrakurikulerModel;

class KehidupanSiswa extends BaseController
{
    public function index(): string
    {
        $kegiatanModel = new KegiatanModel();
        $ekskulModel   = new EkstrakurikulerModel();

        return view('kehidupan_siswa/index', [
            'title'     => 'Kehidupan Siswa & OSIS',
            'kegiatan'  => $kegiatanModel->orderBy('tanggal', 'DESC')->limit(12)->findAll(),
            'upcoming'  => $kegiatanModel->upcoming(5),
            'ekskul'    => $ekskulModel->where('is_active', 1)->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }
}
