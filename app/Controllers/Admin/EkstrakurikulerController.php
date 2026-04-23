<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EkstrakurikulerModel;

class EkstrakurikulerController extends BaseController
{
    private EkstrakurikulerModel $model;

    public function __construct()
    {
        $this->model = new EkstrakurikulerModel();
    }

    public function index(): string
    {
        return view('admin/ekskul/index', [
            'title'  => 'Ekstrakurikuler',
            'ekskul' => $this->model->orderBy('urutan', 'ASC')->findAll(),
            'total'  => $this->model->countAllResults(false),
            'aktif'  => $this->model->where('is_active', 1)->countAllResults(false),
        ]);
    }

    public function create(): string
    {
        return view('admin/ekskul/create', [
            'title'       => 'Tambah Ekstrakurikuler',
            'next_urutan' => ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1,
        ]);
    }

    public function store()
    {
        $rules = ['nama' => 'required|max_length[100]'];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->_saveCroppedFoto();

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto'      => $foto,
            'jadwal'    => $this->request->getPost('jadwal'),
            'pembina'   => $this->request->getPost('pembina'),
            'prestasi'  => $this->request->getPost('prestasi'),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('ekskul'))->with('success', 'Ekstrakurikuler berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $ekskul = $this->model->find($id);
        if (! $ekskul) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        return view('admin/ekskul/edit', ['title' => 'Edit Ekstrakurikuler', 'ekskul' => $ekskul]);
    }

    public function update(int $id)
    {
        $ekskul = $this->model->find($id);
        if (! $ekskul) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan.');
        }

        $rules = ['nama' => 'required|max_length[100]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto    = $ekskul['foto'];
        $newFoto = $this->_saveCroppedFoto();
        if ($newFoto) {
            if ($foto) {
                (new \App\Libraries\ImageUpload())->delete('ekskul', $foto);
            }
            $foto = $newFoto;
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto'      => $foto,
            'jadwal'    => $this->request->getPost('jadwal'),
            'pembina'   => $this->request->getPost('pembina'),
            'prestasi'  => $this->request->getPost('prestasi'),
            'is_active' => (int) ($this->request->getPost('is_active') === '1'),
            'urutan'    => (int) ($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(admin_url('ekskul'))->with('success', 'Data berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $ekskul = $this->model->find($id);
        if (! $ekskul) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if (! empty($ekskul['foto'])) {
            (new \App\Libraries\ImageUpload())->delete('ekskul', $ekskul['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(admin_url('ekskul'))->with('success', 'Data berhasil dihapus.');
    }

    // ─── Import Excel ────────────────────────────────────────────────

    public function importForm(): string
    {
        return view('admin/import/form', [
            'title'       => 'Import Ekstrakurikuler',
            'module_name' => 'Ekstrakurikuler',
            'back_url'    => admin_url('ekskul'),
            'import_url'  => admin_url('ekskul/import'),
            'template_url'=> admin_url('ekskul/import-template'),
            'columns_info' => [
                ['name' => 'nama',      'required' => true,  'info' => 'Nama kegiatan ekstrakurikuler'],
                ['name' => 'deskripsi', 'required' => false, 'info' => 'Deskripsi singkat'],
                ['name' => 'jadwal',    'required' => false, 'info' => 'Contoh: Sabtu 08:00–10:00'],
                ['name' => 'pembina',   'required' => false, 'info' => 'Nama pembina/pelatih'],
                ['name' => 'prestasi',  'required' => false, 'info' => 'Prestasi yang pernah diraih'],
                ['name' => 'is_active', 'required' => false, 'info' => '1 = aktif, 0 = nonaktif (default: 1)'],
                ['name' => 'urutan',    'required' => false, 'info' => 'Angka urutan tampil (default: 0)'],
            ],
        ]);
    }

    public function downloadTemplate()
    {
        $importer = new \App\Libraries\ExcelImport();
        $importer->streamTemplate(
            'template_ekstrakurikuler',
            ['nama', 'deskripsi', 'jadwal', 'pembina', 'prestasi', 'is_active', 'urutan'],
            [['Paskibra', 'Pasukan Pengibar Bendera Sekolah', 'Sabtu 07:00-10:00', 'Pak Hendra', 'Juara 1 Tingkat Kota 2024', '1', '1']],
            [
                'nama'      => 'Wajib diisi. Maksimal 100 karakter.',
                'deskripsi' => 'Opsional. Deskripsi singkat kegiatan.',
                'jadwal'    => 'Opsional. Contoh: Sabtu 07:00-10:00',
                'pembina'   => 'Opsional. Nama guru pembina.',
                'prestasi'  => 'Opsional. Prestasi yang pernah diraih.',
                'is_active' => 'Opsional. 1 = aktif, 0 = nonaktif. Default: 1.',
                'urutan'    => 'Opsional. Angka urutan tampil. Default: 0.',
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

        $tmpPath = $file->getTempName();
        $importer = new \App\Libraries\ExcelImport();
        $rows     = $importer->readRows($tmpPath);

        $success = 0;
        $errors  = [];
        $nextUrutan = ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1;

        foreach ($rows as $entry) {
            $rowNum = $entry['row'];
            $d      = $entry['data'];

            if (empty($d['nama'])) {
                $errors[] = "Baris {$rowNum}: kolom 'nama' wajib diisi.";
                continue;
            }

            $this->model->insert([
                'nama'      => $d['nama'],
                'deskripsi' => $d['deskripsi'] ?? null,
                'jadwal'    => $d['jadwal']    ?? null,
                'pembina'   => $d['pembina']   ?? null,
                'prestasi'  => $d['prestasi']  ?? null,
                'is_active' => isset($d['is_active']) && $d['is_active'] !== '' ? (int) $d['is_active'] : 1,
                'urutan'    => isset($d['urutan']) && $d['urutan'] !== '' ? (int) $d['urutan'] : $nextUrutan++,
            ]);
            $success++;
        }

        if ($errors) {
            session()->setFlashdata('import_errors', $errors);
        }

        return redirect()->to(admin_url('ekskul'))
            ->with('success', "{$success} data ekstrakurikuler berhasil diimpor." . ($errors ? ' ' . count($errors) . ' baris gagal.' : ''));
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

        $dir = FCPATH . 'uploads/ekskul/';
        if (! is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'ekskul_' . bin2hex(random_bytes(8)) . '.jpg';
        file_put_contents($dir . $filename, $imgData);
        return $filename;
    }
}
