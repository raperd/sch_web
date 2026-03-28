<?php

namespace App\Controllers;

use App\Models\PrestasiModel;
use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;

class Prestasi extends BaseController
{
    public function index(): string
    {
        $model               = new PrestasiModel();
        $galeriModel         = new GaleriModel();
        $kategoriGaleriModel = new KategoriGaleriModel();
        $kategori            = $this->request->getGet('kategori') ?? '';
        $tahun               = $this->request->getGet('tahun') ?? '';

        $katPrestasi  = $kategoriGaleriModel->where('slug', 'prestasi')->first();

        $builder = $model->orderBy('tahun', 'DESC')->orderBy('urutan', 'ASC');

        if ($kategori !== '') {
            $builder->where('kategori', $kategori);
        }
        if ($tahun !== '') {
            $builder->where('tahun', $tahun);
        }

        $prestasi = $builder->paginate(12, 'prestasi');

        // Daftar tahun untuk filter
        $tahunList = $model->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll();

        return view('prestasi/index', [
            'title'        => 'Prestasi Sekolah',
            'meta_desc'    => 'Kumpulan prestasi akademik dan non-akademik yang diraih oleh siswa dan sekolah.',
            'prestasi'     => $prestasi,
            'pager'        => $model->pager,
            'tahun_list'   => array_column($tahunList, 'tahun'),
            'kategori_filter' => $kategori,
            'tahun_filter'    => $tahun,
            'total_akademik'     => $model->where('kategori', 'akademik')->countAllResults(true),
            'total_non_akademik' => $model->where('kategori', 'non_akademik')->countAllResults(true),
            'galeri_prestasi'    => $katPrestasi ? $galeriModel->getByKategori($katPrestasi['id']) : [],
        ]);
    }
}
