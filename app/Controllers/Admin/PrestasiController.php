<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrestasiModel;

class PrestasiController extends BaseController
{
    private PrestasiModel $model;

    public function __construct()
    {
        $this->model = new PrestasiModel();
    }

    public function index(): string
    {
        $kategori = $this->request->getGet('kategori') ?? '';
        $tingkat  = $this->request->getGet('tingkat') ?? '';
        $tahun    = $this->request->getGet('tahun') ?? '';
        $search   = $this->request->getGet('q') ?? '';

        $builder = $this->model->orderBy('tahun', 'DESC')->orderBy('urutan', 'ASC');

        if ($kategori !== '') $builder->where('kategori', $kategori);
        if ($tingkat  !== '') $builder->where('tingkat', $tingkat);
        if ($tahun    !== '') $builder->where('tahun', $tahun);
        if ($search   !== '') $builder->like('judul', $search);

        $tahunList = $this->model->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll();

        return view('admin/prestasi/index', [
            'title'              => 'Manajemen Prestasi',
            'prestasi'           => $builder->paginate(20, 'prestasi'),
            'pager'              => $this->model->pager,
            'kategori_filter'    => $kategori,
            'tingkat_filter'     => $tingkat,
            'tahun_filter'       => $tahun,
            'search'             => $search,
            'tahun_list'         => array_column($tahunList, 'tahun'),
            'total_all'          => $this->model->countAllResults(true),
            'total_akademik'     => $this->model->where('kategori', 'akademik')->countAllResults(true),
            'total_non_akademik' => $this->model->where('kategori', 'non_akademik')->countAllResults(true),
            'total_featured'     => $this->model->where('is_featured', 1)->countAllResults(true),
        ]);
    }

    public function create(): string
    {
        return view('admin/prestasi/create', [
            'title'       => 'Tambah Prestasi',
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = [
            'judul'    => 'required|max_length[255]',
            'kategori' => 'required|in_list[akademik,non_akademik]',
            'tingkat'  => 'required|in_list[sekolah,kecamatan,kota_kabupaten,provinsi,nasional,internasional]',
            'tahun'    => 'required|integer|min_length[4]|max_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = null;
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $foto = $uploader->upload('foto', 'prestasi');
        }

        $this->model->insert([
            'judul'       => $this->request->getPost('judul'),
            'kategori'    => $this->request->getPost('kategori'),
            'tingkat'     => $this->request->getPost('tingkat'),
            'tahun'       => (int) $this->request->getPost('tahun'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'nama_siswa'  => $this->request->getPost('nama_siswa') ?: null,
            'pembimbing'  => $this->request->getPost('pembimbing') ?: null,
            'foto'        => $foto,
            'is_featured' => (int) ($this->request->getPost('is_featured') === '1'),
            'urutan'      => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/prestasi'))->with('success', 'Prestasi berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $item = $this->model->find($id);
        if (! $item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Prestasi tidak ditemukan.');
        }

        return view('admin/prestasi/edit', [
            'title'    => 'Edit Prestasi',
            'prestasi' => $item,
        ]);
    }

    public function update(int $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Prestasi tidak ditemukan.');
        }

        $rules = [
            'judul'    => 'required|max_length[255]',
            'kategori' => 'required|in_list[akademik,non_akademik]',
            'tingkat'  => 'required|in_list[sekolah,kecamatan,kota_kabupaten,provinsi,nasional,internasional]',
            'tahun'    => 'required|integer|min_length[4]|max_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $item['foto'];
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $newFoto  = $uploader->upload('foto', 'prestasi');
            if ($newFoto) {
                if ($foto) $uploader->delete('prestasi', $foto);
                $foto = $newFoto;
            }
        }

        $this->model->update($id, [
            'judul'       => $this->request->getPost('judul'),
            'kategori'    => $this->request->getPost('kategori'),
            'tingkat'     => $this->request->getPost('tingkat'),
            'tahun'       => (int) $this->request->getPost('tahun'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'nama_siswa'  => $this->request->getPost('nama_siswa') ?: null,
            'pembimbing'  => $this->request->getPost('pembimbing') ?: null,
            'foto'        => $foto,
            'is_featured' => (int) ($this->request->getPost('is_featured') === '1'),
            'urutan'      => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/prestasi'))->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            return redirect()->back()->with('error', 'Prestasi tidak ditemukan.');
        }

        if (! empty($item['foto'])) {
            (new \App\Libraries\ImageUpload())->delete('prestasi', $item['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/prestasi'))->with('success', 'Prestasi berhasil dihapus.');
    }
}
