<?php

namespace App\Controllers;

use App\Models\PpdbModel;

class Ppdb extends BaseController
{
    public function index(): string
    {
        $ppdbModel = new PpdbModel();

        return view('ppdb/index', [
            'title'        => 'PPDB ' . (setting('ppdb_tahun') ?? ''),
            'persyaratan'  => $ppdbModel->getByTipe('persyaratan'),
            'jadwal'       => $ppdbModel->getByTipe('jadwal'),
            'alur'         => $ppdbModel->getByTipe('alur'),
            'faq'          => $ppdbModel->getByTipe('faq'),
            'info'         => $ppdbModel->getByTipe('info'),
        ]);
    }
}
