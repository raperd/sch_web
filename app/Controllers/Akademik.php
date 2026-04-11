<?php

namespace App\Controllers;

use App\Models\ProgramUnggulanModel;
use App\Models\KurikulumBlokModel;

class Akademik extends BaseController
{
    public function index(): string
    {
        $programModel    = new ProgramUnggulanModel();
        $kurikulumModel  = new KurikulumBlokModel();

        return view('akademik/index', [
            'title'     => 'Akademik',
            'programs'  => $programModel->getAktif(),
            'kurikulums'=> $kurikulumModel->getAktif(),
        ]);
    }
}
