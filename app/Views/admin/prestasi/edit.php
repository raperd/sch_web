<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Edit Prestasi</h4>
        <p class="text-muted small mb-0"><?= esc($prestasi['judul']) ?></p>
    </div>
    <a href="<?= base_url('admin/prestasi') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/prestasi/update/' . $prestasi['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-trophy me-2"></i>Informasi Prestasi</h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control"
                            value="<?= old('judul', esc($prestasi['judul'])) ?>" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select" required>
                                <option value="akademik" <?= old('kategori', $prestasi['kategori']) === 'akademik' ? 'selected' : '' ?>>Akademik</option>
                                <option value="non_akademik" <?= old('kategori', $prestasi['kategori']) === 'non_akademik' ? 'selected' : '' ?>>Non-Akademik</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <?php foreach ([
                                    'sekolah'        => 'Sekolah',
                                    'kecamatan'      => 'Kecamatan',
                                    'kota_kabupaten' => 'Kota/Kabupaten',
                                    'provinsi'       => 'Provinsi',
                                    'nasional'       => 'Nasional',
                                    'internasional'  => 'Internasional',
                                ] as $val => $lbl): ?>
                                    <option value="<?= $val ?>" <?= old('tingkat', $prestasi['tingkat']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control"
                                value="<?= old('tahun', $prestasi['tahun']) ?>"
                                min="2000" max="<?= date('Y') + 1 ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Siswa / Tim</label>
                            <input type="text" name="nama_siswa" class="form-control"
                                value="<?= old('nama_siswa', esc($prestasi['nama_siswa'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pembimbing</label>
                            <input type="text" name="pembimbing" class="form-control"
                                value="<?= old('pembimbing', esc($prestasi['pembimbing'] ?? '')) ?>">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?= old('deskripsi', esc($prestasi['deskripsi'] ?? '')) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-image me-2"></i>Foto</h6>
                    <?php if (!empty($prestasi['foto'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/prestasi/' . esc($prestasi['foto'])) ?>"
                                class="img-fluid rounded" style="max-height:150px;" alt="">
                        </div>
                    <?php endif; ?>
                    <div id="fotoPreview" class="mb-2 d-none">
                        <img id="previewImg" src="" class="img-fluid rounded" alt="">
                    </div>
                    <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
                    <div class="form-text">Upload baru untuk mengganti. Maks 2MB.</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-sliders me-2"></i>Opsi</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control"
                            value="<?= old('urutan', $prestasi['urutan']) ?>" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                            id="isFeatured" <?= old('is_featured', $prestasi['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="isFeatured">
                            <i class="bi bi-star-fill text-warning me-1"></i>Tampilkan di Beranda
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg fw-semibold">
                <i class="bi bi-save me-2"></i>Simpan Perubahan
            </button>
        </div>
    </div>
</form>

<?= $this->section('scripts') ?>
<script>
document.getElementById('fotoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('fotoPreview').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
