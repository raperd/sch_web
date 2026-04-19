<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= admin_url('kegiatan') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Kegiatan</h4>
        <p class="text-muted small mb-0"><?= esc(truncate_text($kegiatan['judul'], 60)) ?></p>
    </div>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Periksa kembali:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= admin_url('kegiatan/update/' . $kegiatan['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" name="judul"
                            value="<?= esc(old('judul', $kegiatan['judul'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi"
                            value="<?= esc(old('lokasi', $kegiatan['lokasi'])) ?>">
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="5"><?= esc(old('deskripsi', $kegiatan['deskripsi'])) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-calendar3 me-1 text-primary"></i>Jadwal
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal"
                                value="<?= esc(old('tanggal', $kegiatan['tanggal'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai <small class="text-muted">(opsional)</small></label>
                            <input type="date" class="form-control" name="tanggal_selesai"
                                value="<?= esc(old('tanggal_selesai', $kegiatan['tanggal_selesai'])) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Status & Tipe -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-sliders me-1 text-primary"></i>Pengaturan
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <?php foreach (['event' => 'Event Sekolah', 'lomba' => 'Lomba / Kompetisi', 'sosial' => 'Bakti Sosial', 'osis' => 'Kegiatan OSIS', 'lainnya' => 'Lainnya'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('tipe', $kegiatan['tipe']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <?php foreach (['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'selesai' => 'Selesai'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('status', $kegiatan['status']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1"
                                <?= old('is_featured', $kegiatan['is_featured']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isFeatured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Kegiatan Unggulan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= admin_url('kegiatan') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>

            <!-- Foto -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>Foto Kegiatan
                </div>
                <div class="card-body p-3">
                    <div class="mb-3 text-center <?= empty($kegiatan['foto']) ? 'd-none' : '' ?>" id="fotoPreviewWrap">
                        <img id="fotoPreview"
                            src="<?= !empty($kegiatan['foto']) ? base_url('uploads/kegiatan/' . esc($kegiatan['foto'])) : '' ?>"
                            style="max-height:120px;border-radius:.5rem;max-width:100%" alt="Foto saat ini">
                        <button type="button" class="btn btn-sm btn-outline-secondary d-block mx-auto mt-2" id="reCropBtn">
                            <i class="bi bi-crop me-1"></i>Ubah Crop
                        </button>
                    </div>
                    <input type="file" id="fotoInput" accept="image/jpeg,image/png,image/webp" style="display:none">
                    <input type="hidden" name="foto_cropped" id="fotoCropped">
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="pickFotoBtn">
                            <i class="bi bi-upload me-1"></i><?= empty($kegiatan['foto']) ? 'Pilih Foto' : 'Ganti Foto' ?>
                        </button>
                        <span class="text-muted small" id="fotoFileName">
                            <?= !empty($kegiatan['foto']) ? esc($kegiatan['foto']) : 'Belum ada foto dipilih' ?>
                        </span>
                    </div>
                    <div class="form-text">Upload baru untuk mengganti. Dipotong otomatis rasio 16:9.</div>
                </div>
            </div>

            <!-- Meta -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <small class="text-muted d-block">
                        <i class="bi bi-calendar3 me-1"></i>Dibuat: <?= format_tanggal($kegiatan['created_at'], 'full') ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Crop Foto Kegiatan -->
<div class="modal fade" id="kegiatanCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Foto (16:9)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="kegiatanCropImg" src="" alt="Crop" style="max-width:100%;display:block;">
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="kegZoomOut"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="kegZoomIn"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="kegRotL"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="kegRotR"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="kegiatanCropConfirm">
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
let kegCropper = null, kegCropApplied = false;
const kegModalEl = document.getElementById('kegiatanCropModal');
const fotoPreviewWrap = document.getElementById('fotoPreviewWrap');

document.getElementById('pickFotoBtn').addEventListener('click', () => document.getElementById('fotoInput').click());
document.getElementById('reCropBtn').addEventListener('click',   () => document.getElementById('fotoInput').click());

document.getElementById('fotoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    // Simpan state sebelum modal dibuka (untuk cancel)
    const wasVisible = !fotoPreviewWrap.classList.contains('d-none');
    const prevSrc    = document.getElementById('fotoPreview').src;

    document.getElementById('fotoFileName').textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('kegiatanCropImg').src = e.target.result;
        bootstrap.Modal.getOrCreateInstance(kegModalEl).show();
        kegModalEl.addEventListener('shown.bs.modal', () => {
            if (kegCropper) kegCropper.destroy();
            kegCropper = new Cropper(document.getElementById('kegiatanCropImg'), {
                aspectRatio: 16 / 9,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
            });
        }, { once: true });

        kegModalEl.addEventListener('hidden.bs.modal', function () {
            if (kegCropper) { kegCropper.destroy(); kegCropper = null; }
            if (!kegCropApplied) {
                document.getElementById('fotoCropped').value = '';
                document.getElementById('fotoInput').value = '';
                document.getElementById('fotoFileName').textContent = wasVisible ? prevSrc.split('/').pop() : 'Belum ada foto dipilih';
                if (wasVisible) {
                    document.getElementById('fotoPreview').src = prevSrc;
                    fotoPreviewWrap.classList.remove('d-none');
                } else {
                    fotoPreviewWrap.classList.add('d-none');
                }
            }
            kegCropApplied = false;
        }, { once: true });
    };
    reader.readAsDataURL(file);
});

document.getElementById('kegZoomIn').addEventListener('click',  () => kegCropper?.zoom(0.1));
document.getElementById('kegZoomOut').addEventListener('click', () => kegCropper?.zoom(-0.1));
document.getElementById('kegRotL').addEventListener('click',    () => kegCropper?.rotate(-90));
document.getElementById('kegRotR').addEventListener('click',    () => kegCropper?.rotate(90));

document.getElementById('kegiatanCropConfirm').addEventListener('click', function () {
    if (!kegCropper) return;
    kegCropper.getCroppedCanvas({ width: 1200, height: 675, imageSmoothingQuality: 'high' }).toBlob(blob => {
        const url = URL.createObjectURL(blob);
        document.getElementById('fotoPreview').src = url;
        fotoPreviewWrap.classList.remove('d-none');
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('fotoCropped').value = e.target.result; };
        reader.readAsDataURL(blob);
        kegCropApplied = true;
        bootstrap.Modal.getInstance(kegModalEl).hide();
        kegCropper.destroy();
        kegCropper = null;
    }, 'image/jpeg', 0.85);
});
</script>
<?= $this->endSection() ?>
