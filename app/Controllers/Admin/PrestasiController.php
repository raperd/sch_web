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

        $tahunList = (new PrestasiModel())->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll();

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

        $foto = $this->_saveCroppedFoto();

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

        $foto    = $item['foto'];
        $newFoto = $this->_saveCroppedFoto();
        if ($newFoto) {
            if ($foto) {
                (new \App\Libraries\ImageUpload())->delete('prestasi', $foto);
            }
            $foto = $newFoto;
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

    private function _saveCroppedFoto(): ?string
    {
        $b64 = $this->request->getPost('foto_cropped');
        if (empty($b64) || ! str_starts_with($b64, 'data:image/')) {
            return null;
        }
        [, $data] = explode(',', $b64, 2);
        $imgData  = base64_decode($data);
        if (! $imgData) return null;

        $img = @imagecreatefromstring($imgData);
        if (! $img) return null;
        imagedestroy($img);

        $dir = FCPATH . 'uploads/prestasi/';
        if (! is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'prestasi_' . bin2hex(random_bytes(8)) . '.jpg';
        file_put_contents($dir . $filename, $imgData);
        return $filename;
    }
}
