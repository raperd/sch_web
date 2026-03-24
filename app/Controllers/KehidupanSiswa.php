<?php

namespace App\Controllers;

class KehidupanSiswa extends BaseController
{
    public function index(): string
    {
        return view('kehidupan_siswa/index', ['title' => 'Kehidupan Siswa & OSIS']);
    }
}
