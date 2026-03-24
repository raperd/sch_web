<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;
use App\Models\GuruStafModel;
use App\Models\GaleriModel;
use App\Models\KegiatanModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $artikelModel  = new ArtikelModel();
        $guruModel     = new GuruStafModel();
        $galeriModel   = new GaleriModel();
        $kegiatanModel = new KegiatanModel();

        $data = [
            'title'            => 'Dashboard',
            'total_artikel'    => $artikelModel->where('status', 'published')->countAllResults(),
            'total_draft'      => $artikelModel->where('status', 'draft')->countAllResults(),
            'total_guru'       => $guruModel->where('is_active', 1)->where('tipe', 'guru')->countAllResults(),
            'total_staf'       => $guruModel->where('is_active', 1)->whereIn('tipe', ['staf', 'tendik'])->countAllResults(),
            'total_galeri'     => $galeriModel->countAll(),
            'total_kegiatan'   => $kegiatanModel->whereIn('status', ['upcoming', 'ongoing'])->countAllResults(),
            'artikel_terbaru'  => $artikelModel->withRelations()->orderBy('artikel.created_at', 'DESC')->limit(5)->findAll(),
            'kegiatan_upcoming'=> $kegiatanModel->upcoming(5),
        ];

        return view('admin/dashboard/index', $data);
    }
}
