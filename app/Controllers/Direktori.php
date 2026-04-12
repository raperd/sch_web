<?php

namespace App\Controllers;

use App\Models\GuruStafModel;
use App\Models\BidangGuruModel;

class Direktori extends BaseController
{
    public function index(): string
    {
        $guruModel  = new GuruStafModel();
        $bidangModel = new BidangGuruModel();

        return view('direktori/index', [
            'title'   => 'Direktori Guru & Staf',
            'guru'    => $guruModel->getByTipe('guru'),
            'staf'    => $guruModel->getByTipe('staf'),
            'tendik'  => $guruModel->getByTipe('tendik'),
            'alumni'  => (new GuruStafModel())->getAlumni(),
            'bidang'  => $bidangModel->findAll(),
        ]);
    }
}
