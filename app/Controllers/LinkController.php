<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AplikasiModel;

class LinkController extends BaseController
{
    public function index(): string
    {
        $aplikasiModel = new AplikasiModel();

        return view('link_terkait/index', [
            'title'    => 'Link Aplikasi Terkait',
            'aplikasi' => $aplikasiModel->getActive(),
        ]);
    }
}
