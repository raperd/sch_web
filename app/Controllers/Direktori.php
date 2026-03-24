<?php

namespace App\Controllers;

class Direktori extends BaseController
{
    public function index(): string
    {
        return view('direktori/index', ['title' => 'Direktori Guru & Staf']);
    }
}
