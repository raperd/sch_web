<?php

namespace App\Controllers;

use App\Models\AlbumFotoModel;

class AlbumFoto extends BaseController
{
    public function index(): string
    {
        helper('app');
        $model = new AlbumFotoModel();
        return view('album_foto/index', [
            'title'     => 'Album Foto',
            'meta_desc' => 'Kumpulan album foto kegiatan dan momen sekolah kami di Google Foto.',
            'albums'    => $model->getPublished(),
        ]);
    }
}
