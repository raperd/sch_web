<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruStafModel;
use App\Models\BidangGuruModel;

class GuruController extends BaseController
{
    private GuruStafModel $model;
    private BidangGuruModel $bidangModel;

    public function __construct()
    {
        $this->model      = new GuruStafModel();
        $this->bidangModel = new BidangGuruModel();
    }

    public function index(): string
    {
        $tipe   = $this->request->getGet('tipe') ?? '';
        $search = $this->request->getGet('q') ?? '';

        $builder = $this->model
            ->select('guru_staf.*, bidang_guru.nama as bidang_nama')
            ->join('bidang_guru', 'bidang_guru.id = guru_staf.bidang_id', 'left')
            ->orderBy('guru_staf.urutan', 'ASC')
            ->orderBy('guru_staf.nama', 'ASC');

        if ($tipe !== '') {
            $builder->where('guru_staf.tipe', $tipe);
        }
        if ($search !== '') {
            $builder->groupStart()
                ->like('guru_staf.nama', $search)
                ->orLike('guru_staf.jabatan', $search)
                ->groupEnd();
        }

        return view('admin/guru/index', [
            'title'       => 'Manajemen Guru & Staf',
            'guru'        => $builder->paginate(20, 'guru'),
            'pager'       => $this->model->pager,
            'tipe_filter' => $tipe,
            'search'      => $search,
            'total_all'   => $this->model->countAllResults(false),
            'total_guru'  => $this->model->where('tipe', 'guru')->countAllResults(false),
            'total_staf'  => $this->model->where('tipe', 'staf')->countAllResults(false),
            'total_tendik'=> $this->model->where('tipe', 'tendik')->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/guru/create', [
            'title'  => 'Tambah Guru / Staf',
            'bidang' => $this->bidangModel->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'    => 'required|max_length[100]',
            'jabatan' => 'required|max_length[100]',
            'tipe'    => 'required|in_list[guru,staf,tendik]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = null;
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $foto = $uploader->upload('foto', 'guru');
            if (! $foto) {
                return redirect()->back()->withInput()->with('error', 'Gagal upload foto.');
            }
        }

        $this->model->insert([
            'bidang_id'          => $this->request->getPost('bidang_id') ?: null,
            'nip'                => $this->request->getPost('nip'),
            'nama'               => $this->request->getPost('nama'),
            'jabatan'            => $this->request->getPost('jabatan'),
            'tipe'               => $this->request->getPost('tipe'),
            'mata_pelajaran'     => $this->request->getPost('mata_pelajaran'),
            'pendidikan'         => $this->request->getPost('pendidikan'),
            'foto'               => $foto,
            'filosofi_mengajar'  => $this->request->getPost('filosofi_mengajar'),
            'email_publik'       => $this->request->getPost('email_publik'),
            'is_active'          => (int) ($this->request->getPost('is_active') === '1'),
            'urutan'             => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/guru'))->with('success', 'Data guru/staf berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $guru = $this->model->find($id);
        if (! $guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        return view('admin/guru/edit', [
            'title'  => 'Edit Guru / Staf',
            'guru'   => $guru,
            'bidang' => $this->bidangModel->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $guru = $this->model->find($id);
        if (! $guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        $rules = [
            'nama'    => 'required|max_length[100]',
            'jabatan' => 'required|max_length[100]',
            'tipe'    => 'required|in_list[guru,staf,tendik]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $guru['foto'];
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploader = new \App\Libraries\ImageUpload();
            $newFoto = $uploader->upload('foto', 'guru');
            if ($newFoto) {
                if ($foto) {
                    $uploader->delete('guru', $foto);
                }
                $foto = $newFoto;
            }
        }

        $this->model->update($id, [
            'bidang_id'         => $this->request->getPost('bidang_id') ?: null,
            'nip'               => $this->request->getPost('nip'),
            'nama'              => $this->request->getPost('nama'),
            'jabatan'           => $this->request->getPost('jabatan'),
            'tipe'              => $this->request->getPost('tipe'),
            'mata_pelajaran'    => $this->request->getPost('mata_pelajaran'),
            'pendidikan'        => $this->request->getPost('pendidikan'),
            'foto'              => $foto,
            'filosofi_mengajar' => $this->request->getPost('filosofi_mengajar'),
            'email_publik'      => $this->request->getPost('email_publik'),
            'is_active'         => (int) ($this->request->getPost('is_active') === '1'),
            'urutan'            => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('admin/guru'))->with('success', 'Data berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $guru = $this->model->find($id);
        if (! $guru) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if (! empty($guru['foto'])) {
            (new \App\Libraries\ImageUpload())->delete('guru', $guru['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('admin/guru'))->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Terima JSON [{id, urutan}, ...] — update urutan via AJAX drag-drop.
     * Response: JSON {success: bool}
     */
    public function updateUrutan()
    {
        $body = $this->request->getJSON(true);
        if (! is_array($body)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payload']);
        }

        foreach ($body as $item) {
            if (isset($item['id'], $item['urutan'])) {
                $this->model->update((int) $item['id'], ['urutan' => (int) $item['urutan']]);
            }
        }

        return $this->response->setJSON(['success' => true]);
    }
}
