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
            'title'       => 'Tambah Guru / Staf',
            'bidang'      => $this->bidangModel->orderBy('nama', 'ASC')->findAll(),
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
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
            'tahun_masuk'        => $this->request->getPost('tahun_masuk')  ?: null,
            'tahun_keluar'       => $this->request->getPost('tahun_keluar') ?: null,
            'status_keluar'      => $this->request->getPost('status_keluar') ?: null,
        ]);

        return redirect()->to(admin_url('guru'))->with('success', 'Data guru/staf berhasil disimpan.');
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
            'tahun_masuk'       => $this->request->getPost('tahun_masuk')  ?: null,
            'tahun_keluar'      => $this->request->getPost('tahun_keluar') ?: null,
            'status_keluar'     => $this->request->getPost('status_keluar') ?: null,
        ]);

        return redirect()->to(admin_url('guru'))->with('success', 'Data berhasil diperbarui.');
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
        return redirect()->to(admin_url('guru'))->with('success', 'Data berhasil dihapus.');
    }

    public function toggleActive(int $id)
    {
        $guru = $this->model->find($id);
        if (! $guru) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->model->update($id, ['is_active' => $guru['is_active'] == 1 ? 0 : 1]);

        $status = $guru['is_active'] == 1 ? 'dinonaktifkan' : 'diaktifkan';
        return redirect()->to(admin_url('guru'))->with('success', "Guru/staf berhasil {$status}.");
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

    // ─── Import Excel ────────────────────────────────────────────────

    public function importForm(): string
    {
        return view('admin/import/form', [
            'title'        => 'Import Guru & Staf',
            'module_name'  => 'Guru & Staf',
            'back_url'     => admin_url('guru'),
            'import_url'   => admin_url('guru/import'),
            'template_url' => admin_url('guru/import-template'),
            'columns_info' => [
                ['name' => 'nama',           'required' => true,  'info' => 'Nama lengkap'],
                ['name' => 'jabatan',        'required' => true,  'info' => 'Jabatan, contoh: Guru Matematika'],
                ['name' => 'tipe',           'required' => true,  'info' => 'guru / staf / tendik'],
                ['name' => 'nip',            'required' => false, 'info' => 'Nomor Induk Pegawai'],
                ['name' => 'mata_pelajaran', 'required' => false, 'info' => 'Mata pelajaran yang diampu'],
                ['name' => 'pendidikan',     'required' => false, 'info' => 'Pendidikan terakhir, contoh: S1 Matematika'],
                ['name' => 'email_publik',   'required' => false, 'info' => 'Email yang bisa dilihat publik'],
                ['name' => 'tahun_masuk',    'required' => false, 'info' => 'Tahun bergabung (4 digit)'],
                ['name' => 'tahun_keluar',   'required' => false, 'info' => 'Tahun berhenti (kosong = masih aktif)'],
                ['name' => 'status_keluar',  'required' => false, 'info' => 'purna_tugas atau mutasi'],
                ['name' => 'is_active',      'required' => false, 'info' => '1 = aktif, 0 = nonaktif (default: 1)'],
                ['name' => 'urutan',         'required' => false, 'info' => 'Angka urutan tampil (default: 0)'],
            ],
        ]);
    }

    public function downloadTemplate()
    {
        $importer = new \App\Libraries\ExcelImport();
        $importer->streamTemplate(
            'template_guru_staf',
            ['nama', 'jabatan', 'tipe', 'nip', 'mata_pelajaran', 'pendidikan', 'email_publik', 'tahun_masuk', 'tahun_keluar', 'status_keluar', 'is_active', 'urutan'],
            [['Budi Santoso', 'Guru Matematika', 'guru', '198501012010011001', 'Matematika', 'S1 Matematika UGM', 'budi@example.com', '2010', '', '', '1', '1']],
            [
                'nama'           => 'Wajib. Nama lengkap. Maks. 100 karakter.',
                'jabatan'        => 'Wajib. Jabatan di sekolah. Maks. 100 karakter.',
                'tipe'           => 'Wajib. Pilihan: guru, staf, tendik.',
                'nip'            => 'Opsional. Nomor Induk Pegawai.',
                'mata_pelajaran' => 'Opsional. Mata pelajaran yang diampu (untuk tipe guru).',
                'pendidikan'     => 'Opsional. Riwayat pendidikan terakhir.',
                'email_publik'   => 'Opsional. Alamat email publik.',
                'tahun_masuk'    => 'Opsional. Tahun bergabung, 4 digit.',
                'tahun_keluar'   => 'Opsional. Tahun berhenti. Kosongkan jika masih aktif.',
                'status_keluar'  => 'Opsional. purna_tugas atau mutasi.',
                'is_active'      => 'Opsional. 1 = aktif, 0 = nonaktif. Default: 1.',
                'urutan'         => 'Opsional. Angka urutan tampil. Default: 0.',
            ]
        );
    }

    public function import()
    {
        $file = $this->request->getFile('import_file');

        if (! $file || ! $file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }
        if ($file->getSize() > 5 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file melebihi 5 MB.');
        }

        $validTipe         = ['guru', 'staf', 'tendik'];
        $validStatusKeluar = ['purna_tugas', 'mutasi'];

        $importer   = new \App\Libraries\ExcelImport();
        $rows       = $importer->readRows($file->getTempName());

        $success    = 0;
        $errors     = [];
        $nextUrutan = ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1;

        foreach ($rows as $entry) {
            $rowNum = $entry['row'];
            $d      = $entry['data'];

            if (empty($d['nama'])) {
                $errors[] = "Baris {$rowNum}: kolom 'nama' wajib diisi.";
                continue;
            }
            if (empty($d['jabatan'])) {
                $errors[] = "Baris {$rowNum}: kolom 'jabatan' wajib diisi.";
                continue;
            }
            if (! in_array($d['tipe'] ?? '', $validTipe)) {
                $errors[] = "Baris {$rowNum}: 'tipe' harus guru, staf, atau tendik (diterima: '{$d['tipe']}').";
                continue;
            }

            $statusKeluar = isset($d['status_keluar']) && in_array($d['status_keluar'], $validStatusKeluar)
                ? $d['status_keluar'] : null;

            $this->model->insert([
                'nama'           => $d['nama'],
                'jabatan'        => $d['jabatan'],
                'tipe'           => $d['tipe'],
                'nip'            => $d['nip']            ?? null ?: null,
                'mata_pelajaran' => $d['mata_pelajaran'] ?? null ?: null,
                'pendidikan'     => $d['pendidikan']     ?? null ?: null,
                'email_publik'   => $d['email_publik']   ?? null ?: null,
                'tahun_masuk'    => isset($d['tahun_masuk'])  && preg_match('/^\d{4}$/', $d['tahun_masuk'])  ? (int) $d['tahun_masuk']  : null,
                'tahun_keluar'   => isset($d['tahun_keluar']) && preg_match('/^\d{4}$/', $d['tahun_keluar']) ? (int) $d['tahun_keluar'] : null,
                'status_keluar'  => $statusKeluar,
                'is_active'      => isset($d['is_active']) && $d['is_active'] !== '' ? (int) $d['is_active'] : 1,
                'urutan'         => isset($d['urutan'])    && $d['urutan']    !== '' ? (int) $d['urutan']    : $nextUrutan++,
            ]);
            $success++;
        }

        if ($errors) {
            session()->setFlashdata('import_errors', $errors);
        }

        return redirect()->to(admin_url('guru'))
            ->with('success', "{$success} data guru/staf berhasil diimpor." . ($errors ? ' ' . count($errors) . ' baris gagal.' : ''));
    }
}
