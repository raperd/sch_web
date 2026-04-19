<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KegiatanModel;

class KegiatanController extends BaseController
{
    private KegiatanModel $model;

    public function __construct()
    {
        $this->model = new KegiatanModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status') ?? '';
        $tipe   = $this->request->getGet('tipe') ?? '';
        $search = $this->request->getGet('q') ?? '';

        $builder = $this->model->orderBy('tanggal', 'DESC');

        if ($status !== '') {
            $builder->where('status', $status);
        }
        if ($tipe !== '') {
            $builder->where('tipe', $tipe);
        }
        if ($search !== '') {
            $builder->like('judul', $search);
        }

        return view('admin/kegiatan/index', [
            'title'         => 'Manajemen Kegiatan',
            'kegiatan'      => $builder->paginate(15, 'kegiatan'),
            'pager'         => $this->model->pager,
            'status_filter' => $status,
            'tipe_filter'   => $tipe,
            'search'        => $search,
            'total_all'      => $this->model->countAllResults(false),
            'total_upcoming' => $this->model->where('status', 'upcoming')->countAllResults(false),
            'total_ongoing'  => $this->model->where('status', 'ongoing')->countAllResults(false),
            'total_selesai'  => $this->model->where('status', 'selesai')->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/kegiatan/create', [
            'title' => 'Tambah Kegiatan',
        ]);
    }

    public function store()
    {
        $rules = [
            'judul'   => 'required|max_length[255]',
            'tanggal' => 'required|valid_date[Y-m-d]',
            'tipe'    => 'required|in_list[event,lomba,sosial,osis,lainnya]',
            'status'  => 'required|in_list[upcoming,ongoing,selesai]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->_saveCroppedFoto();

        $tanggalSelesai = $this->request->getPost('tanggal_selesai');

        $this->model->insert([
            'judul'          => $this->request->getPost('judul'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'tanggal'        => $this->request->getPost('tanggal'),
            'tanggal_selesai'=> $tanggalSelesai ?: null,
            'lokasi'         => $this->request->getPost('lokasi'),
            'foto'           => $foto,
            'tipe'           => $this->request->getPost('tipe'),
            'status'         => $this->request->getPost('status'),
            'is_featured'    => (int) ($this->request->getPost('is_featured') === '1'),
        ]);

        return redirect()->to(admin_url('kegiatan'))->with('success', 'Kegiatan berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $kegiatan = $this->model->find($id);
        if (! $kegiatan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kegiatan tidak ditemukan.');
        }

        return view('admin/kegiatan/edit', [
            'title'    => 'Edit Kegiatan',
            'kegiatan' => $kegiatan,
        ]);
    }

    public function update(int $id)
    {
        $kegiatan = $this->model->find($id);
        if (! $kegiatan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kegiatan tidak ditemukan.');
        }

        $rules = [
            'judul'   => 'required|max_length[255]',
            'tanggal' => 'required|valid_date[Y-m-d]',
            'tipe'    => 'required|in_list[event,lomba,sosial,osis,lainnya]',
            'status'  => 'required|in_list[upcoming,ongoing,selesai]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto    = $kegiatan['foto'];
        $newFoto = $this->_saveCroppedFoto();
        if ($newFoto) {
            if ($foto) {
                (new \App\Libraries\ImageUpload())->delete('kegiatan', $foto);
            }
            $foto = $newFoto;
        }

        $tanggalSelesai = $this->request->getPost('tanggal_selesai');

        $this->model->update($id, [
            'judul'          => $this->request->getPost('judul'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'tanggal'        => $this->request->getPost('tanggal'),
            'tanggal_selesai'=> $tanggalSelesai ?: null,
            'lokasi'         => $this->request->getPost('lokasi'),
            'foto'           => $foto,
            'tipe'           => $this->request->getPost('tipe'),
            'status'         => $this->request->getPost('status'),
            'is_featured'    => (int) ($this->request->getPost('is_featured') === '1'),
        ]);

        return redirect()->to(admin_url('kegiatan'))->with('success', 'Kegiatan berhasil diperbarui.');
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

        $dir = FCPATH . 'uploads/kegiatan/';
        if (! is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'kegiatan_' . bin2hex(random_bytes(8)) . '.jpg';
        file_put_contents($dir . $filename, $imgData);
        return $filename;
    }

    public function delete(int $id)
    {
        $kegiatan = $this->model->find($id);
        if (! $kegiatan) {
            return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
        }

        if (! empty($kegiatan['foto'])) {
            (new \App\Libraries\ImageUpload())->delete('kegiatan', $kegiatan['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(admin_url('kegiatan'))->with('success', 'Kegiatan berhasil dihapus.');
    }
}
