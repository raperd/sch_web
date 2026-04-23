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

        return redirect()->to(admin_url('prestasi'))->with('success', 'Prestasi berhasil disimpan.');
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

        return redirect()->to(admin_url('prestasi'))->with('success', 'Prestasi berhasil diperbarui.');
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
        return redirect()->to(admin_url('prestasi'))->with('success', 'Prestasi berhasil dihapus.');
    }

    // ─── Import Excel ────────────────────────────────────────────────

    public function importForm(): string
    {
        return view('admin/import/form', [
            'title'        => 'Import Prestasi',
            'module_name'  => 'Prestasi',
            'back_url'     => admin_url('prestasi'),
            'import_url'   => admin_url('prestasi/import'),
            'template_url' => admin_url('prestasi/import-template'),
            'columns_info' => [
                ['name' => 'judul',       'required' => true,  'info' => 'Judul atau nama prestasi'],
                ['name' => 'kategori',    'required' => true,  'info' => 'akademik atau non_akademik'],
                ['name' => 'tingkat',     'required' => true,  'info' => 'sekolah / kecamatan / kota_kabupaten / provinsi / nasional / internasional'],
                ['name' => 'tahun',       'required' => true,  'info' => 'Tahun 4 digit, contoh: 2024'],
                ['name' => 'nama_siswa',  'required' => false, 'info' => 'Nama siswa peraih prestasi'],
                ['name' => 'pembimbing',  'required' => false, 'info' => 'Nama guru pembimbing'],
                ['name' => 'deskripsi',   'required' => false, 'info' => 'Keterangan tambahan'],
                ['name' => 'is_featured', 'required' => false, 'info' => '1 = unggulan, 0 = biasa (default: 0)'],
                ['name' => 'urutan',      'required' => false, 'info' => 'Angka urutan tampil (default: 0)'],
            ],
        ]);
    }

    public function downloadTemplate()
    {
        $importer = new \App\Libraries\ExcelImport();
        $importer->streamTemplate(
            'template_prestasi',
            ['judul', 'kategori', 'tingkat', 'tahun', 'nama_siswa', 'pembimbing', 'deskripsi', 'is_featured', 'urutan'],
            [['Juara 1 Olimpiade Matematika Provinsi', 'akademik', 'provinsi', date('Y'), 'Ahmad Faris', 'Ibu Siti, S.Pd', 'Medali emas olimpiade sains', '1', '1']],
            [
                'judul'       => 'Wajib. Judul prestasi. Maks. 255 karakter.',
                'kategori'    => 'Wajib. Isi: akademik atau non_akademik.',
                'tingkat'     => 'Wajib. Pilihan: sekolah, kecamatan, kota_kabupaten, provinsi, nasional, internasional.',
                'tahun'       => 'Wajib. Tahun 4 digit. Contoh: 2024.',
                'nama_siswa'  => 'Opsional. Nama siswa/tim peraih prestasi.',
                'pembimbing'  => 'Opsional. Nama guru pembimbing.',
                'deskripsi'   => 'Opsional. Keterangan tambahan.',
                'is_featured' => 'Opsional. 1 = unggulan ditampilkan di beranda, 0 = biasa. Default: 0.',
                'urutan'      => 'Opsional. Angka urutan tampil. Default: 0.',
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

        $validKategori = ['akademik', 'non_akademik'];
        $validTingkat  = ['sekolah', 'kecamatan', 'kota_kabupaten', 'provinsi', 'nasional', 'internasional'];

        $importer = new \App\Libraries\ExcelImport();
        $rows     = $importer->readRows($file->getTempName());

        $success    = 0;
        $errors     = [];
        $nextUrutan = ($this->model->selectMax('urutan')->first()['urutan'] ?? 0) + 1;

        foreach ($rows as $entry) {
            $rowNum = $entry['row'];
            $d      = $entry['data'];

            if (empty($d['judul'])) {
                $errors[] = "Baris {$rowNum}: kolom 'judul' wajib diisi.";
                continue;
            }
            if (! in_array($d['kategori'] ?? '', $validKategori)) {
                $errors[] = "Baris {$rowNum}: 'kategori' harus akademik atau non_akademik (diterima: '{$d['kategori']}').";
                continue;
            }
            if (! in_array($d['tingkat'] ?? '', $validTingkat)) {
                $errors[] = "Baris {$rowNum}: 'tingkat' tidak valid (diterima: '{$d['tingkat']}').";
                continue;
            }
            if (empty($d['tahun']) || ! preg_match('/^\d{4}$/', $d['tahun'])) {
                $errors[] = "Baris {$rowNum}: 'tahun' harus 4 digit angka.";
                continue;
            }

            $this->model->insert([
                'judul'       => $d['judul'],
                'kategori'    => $d['kategori'],
                'tingkat'     => $d['tingkat'],
                'tahun'       => (int) $d['tahun'],
                'nama_siswa'  => $d['nama_siswa']  ?? null ?: null,
                'pembimbing'  => $d['pembimbing']  ?? null ?: null,
                'deskripsi'   => $d['deskripsi']   ?? null ?: null,
                'is_featured' => isset($d['is_featured']) && $d['is_featured'] !== '' ? (int) $d['is_featured'] : 0,
                'urutan'      => isset($d['urutan']) && $d['urutan'] !== '' ? (int) $d['urutan'] : $nextUrutan++,
            ]);
            $success++;
        }

        if ($errors) {
            session()->setFlashdata('import_errors', $errors);
        }

        return redirect()->to(admin_url('prestasi'))
            ->with('success', "{$success} prestasi berhasil diimpor." . ($errors ? ' ' . count($errors) . ' baris gagal.' : ''));
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
