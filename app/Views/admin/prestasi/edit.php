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
                                <?php foreach (
                                    [
                                        'sekolah'        => 'Sekolah',
                                        'kecamatan'      => 'Kecamatan',
                                        'kota_kabupaten' => 'Kota/Kabupaten',
                                        'provinsi'       => 'Provinsi',
                                        'nasional'       => 'Nasional',
                                        'internasional'  => 'Internasional',
                                    ] as $val => $lbl
                                ): ?>
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
                    <div class="mb-2 text-center <?= empty($prestasi['foto']) ? 'd-none' : '' ?>" id="fotoWrap">
                        <img id="previewImg"
                            src="<?= !empty($prestasi['foto']) ? base_url('uploads/prestasi/' . esc($prestasi['foto'])) : '' ?>"
                            class="img-fluid rounded" style="max-height:150px;" alt="">
                        <button type="button" class="btn btn-sm btn-outline-secondary d-block mx-auto mt-2" id="reCropBtn">
                            <i class="bi bi-crop me-1"></i>Ubah Crop
                        </button>
                    </div>
                    <input type="file" id="fotoInput" accept="image/jpeg,image/png,image/webp" style="display:none">
                    <input type="hidden" name="foto_cropped" id="fotoCropped">
                    <div class="d-flex gap-2 align-items-center mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="pickFotoBtn">
                            <i class="bi bi-upload me-1"></i>Ganti Foto
                        </button>
                        <span class="text-muted small" id="fotoFileName">
                            <?= !empty($prestasi['foto']) ? esc($prestasi['foto']) : 'Belum ada foto' ?>
                        </span>
                    </div>
                    <div class="form-text">Upload baru untuk mengganti. Dipotong otomatis rasio 16:9.</div>
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

<!-- Modal Crop Foto Prestasi -->
<div class="modal fade" id="prestasiCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Foto (16:9)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="prestasiCropImg" src="" alt="Crop" style="max-width:100%;display:block;">
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="preZoomOut"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="preZoomIn"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="preRotL"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="preRotR"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="prestasiCropConfirm">
                        <i class="bi bi-check-lg me-1"></i>Crop &amp; Gunakan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let preCropper = null,
        preCropApplied = false;
    const preModalEl = document.getElementById('prestasiCropModal');
    const fotoWrap = document.getElementById('fotoWrap');

    document.getElementById('pickFotoBtn').addEventListener('click', () => document.getElementById('fotoInput').click());
    document.getElementById('reCropBtn').addEventListener('click', () => document.getElementById('fotoInput').click());

    document.getElementById('fotoInput').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const wasVisible = !fotoWrap.classList.contains('d-none');
        const prevSrc = document.getElementById('previewImg').src;

        document.getElementById('fotoFileName').textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('prestasiCropImg').src = e.target.result;
            bootstrap.Modal.getOrCreateInstance(preModalEl).show();
            preModalEl.addEventListener('shown.bs.modal', () => {
                if (preCropper) preCropper.destroy();
                preCropper = new Cropper(document.getElementById('prestasiCropImg'), {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                });
            }, {
                once: true
            });

            preModalEl.addEventListener('hidden.bs.modal', function() {
                if (preCropper) {
                    preCropper.destroy();
                    preCropper = null;
                }
                if (!preCropApplied) {
                    document.getElementById('fotoCropped').value = '';
                    document.getElementById('fotoInput').value = '';
                    document.getElementById('fotoFileName').textContent = wasVisible ? prevSrc.split('/').pop() : 'Belum ada foto';
                    if (wasVisible) {
                        document.getElementById('previewImg').src = prevSrc;
                        fotoWrap.classList.remove('d-none');
                    } else {
                        fotoWrap.classList.add('d-none');
                    }
                }
                preCropApplied = false;
            }, {
                once: true
            });
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('preZoomIn').addEventListener('click', () => preCropper?.zoom(0.1));
    document.getElementById('preZoomOut').addEventListener('click', () => preCropper?.zoom(-0.1));
    document.getElementById('preRotL').addEventListener('click', () => preCropper?.rotate(-90));
    document.getElementById('preRotR').addEventListener('click', () => preCropper?.rotate(90));

    document.getElementById('prestasiCropConfirm').addEventListener('click', function() {
        if (!preCropper) return;
        preCropper.getCroppedCanvas({
            width: 1200,
            height: 675,
            imageSmoothingQuality: 'high'
        }).toBlob(blob => {
            const url = URL.createObjectURL(blob);
            document.getElementById('previewImg').src = url;
            fotoWrap.classList.remove('d-none');
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('fotoCropped').value = e.target.result;
            };
            reader.readAsDataURL(blob);
            preCropApplied = true;
            bootstrap.Modal.getInstance(preModalEl).hide();
            preCropper.destroy();
            preCropper = null;
        }, 'image/jpeg', 0.85);
    });
</script>
<?= $this->endSection() ?>