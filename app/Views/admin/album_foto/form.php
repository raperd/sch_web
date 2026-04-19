<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">
<style>
#cropImageAlbum { max-width: 100%; display: block; }
.cover-preview { width: 100%; max-width: 360px; aspect-ratio: 16/9; object-fit: cover; border-radius: .5rem; border: 3px solid var(--bs-primary); display: block; }
.cover-placeholder { width: 100%; max-width: 360px; aspect-ratio: 16/9; border-radius: .5rem; background: #e9ecef; display: flex; align-items: center; justify-content: center; border: 3px dashed #ced4da; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= admin_url('album-foto') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><?= $album ? 'Edit' : 'Tambah' ?> Album Foto</h4>
        <small class="text-muted">Isi data album dan tautkan ke Google Foto</small>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php $action = $album
    ? admin_url('album-foto/update/' . $album['id'])
    : admin_url('album-foto/store'); ?>

<form method="POST" action="<?= $action ?>" enctype="multipart/form-data" id="albumFotoForm">
    <?= csrf_field() ?>
    <input type="hidden" name="cover_cropped" id="coverCropped">

    <div class="row g-4">

        <!-- Cover Foto -->
        <div class="col-lg-3 text-center">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center gap-3">
                    <label class="form-label fw-semibold w-100 text-start">Cover Foto</label>

                    <div id="coverPreviewWrap">
                        <?php if (!empty($album['cover_foto'])): ?>
                            <img id="coverPreview"
                                src="<?= base_url('uploads/album_foto/' . esc($album['cover_foto'])) ?>"
                                class="cover-preview" alt="Cover">
                        <?php else: ?>
                            <div class="cover-placeholder" id="coverPlaceholder">
                                <i class="bi bi-images fs-1 text-muted"></i>
                            </div>
                            <img id="coverPreview" src="" class="cover-preview d-none" alt="Cover">
                        <?php endif; ?>
                    </div>

                    <input type="file" name="cover_foto" id="coverInput"
                        accept="image/jpeg,image/png,image/webp" style="display:none">

                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="pickCoverBtn">
                        <i class="bi bi-upload me-1"></i>
                        <?= !empty($album['cover_foto']) ? 'Ganti Cover' : 'Pilih Cover' ?>
                    </button>
                    <div class="form-text text-center">Foto cover 16:9 (landscape).<br>Output: 800×450 px.</div>
                </div>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">

                        <!-- Judul -->
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="judulInput">
                                Judul <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="judulInput" name="judul" class="form-control" required maxlength="255"
                                value="<?= esc(old('judul', $album['judul'] ?? '')) ?>"
                                placeholder="Contoh: Peringatan HUT RI ke-79">
                        </div>

                        <!-- Link Google Foto -->
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="linkGfoto">
                                <i class="bi bi-google me-1 text-danger"></i>Link Google Foto <span class="text-danger">*</span>
                            </label>
                            <input type="url" id="linkGfoto" name="link_google_foto" class="form-control" required maxlength="500"
                                value="<?= esc(old('link_google_foto', $album['link_google_foto'] ?? '')) ?>"
                                placeholder="https://photos.google.com/share/...">
                            <div class="form-text">Tempel tautan berbagi album Google Foto di sini.</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="deskripsiInput">Deskripsi</label>
                            <textarea id="deskripsiInput" name="deskripsi" class="form-control" rows="3"
                                placeholder="Deskripsi singkat album (opsional)..."><?= esc(old('deskripsi', $album['deskripsi'] ?? '')) ?></textarea>
                        </div>

                        <!-- Tanggal & Urutan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="tanggalInput">Tanggal</label>
                            <input type="date" id="tanggalInput" name="tanggal" class="form-control"
                                value="<?= esc(old('tanggal', $album['tanggal'] ?? '')) ?>">
                            <div class="form-text">Untuk keperluan tampilan dan pengurutan.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="urutanInput">Urutan</label>
                            <input type="number" id="urutanInput" name="urutan" class="form-control" min="0"
                                value="<?= esc(old('urutan', $album['urutan'] ?? ($next_urutan ?? 0))) ?>">
                            <div class="form-text">0 = tampil berdasar tanggal.</div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end pb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isPublished"
                                    name="is_published" value="1"
                                    <?= (old('is_published', $album['is_published'] ?? 1)) == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="isPublished">
                                    Tampilkan
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                    <a href="<?= admin_url('album-foto') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>
                        <?= $album ? 'Simpan Perubahan' : 'Tambah Album' ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>

<!-- Modal Crop Cover -->
<div class="modal fade" id="albumCoverCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Cover (16:9)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="cropImageAlbum" src="" alt="Crop">
            </div>
            <div class="modal-footer justify-content-between">
                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Cover akan di-crop menjadi landscape 16:9 (800×450 px).</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="albumCropConfirm">
                        <i class="bi bi-check-lg me-1"></i>Potong &amp; Gunakan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
let albumCropper = null, albumCropApplied = false;

document.getElementById('pickCoverBtn').addEventListener('click', () => {
    document.getElementById('coverInput').click();
});

document.getElementById('coverInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('cropImageAlbum').src = e.target.result;
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('albumCoverCropModal'));
        modal.show();
        document.getElementById('albumCoverCropModal').addEventListener('shown.bs.modal', () => {
            if (albumCropper) albumCropper.destroy();
            albumCropper = new Cropper(document.getElementById('cropImageAlbum'), {
                aspectRatio: 16 / 9,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
            });
        }, { once: true });
    };
    reader.readAsDataURL(file);
});

document.getElementById('albumCropConfirm').addEventListener('click', function () {
    if (!albumCropper) return;
    const canvas = albumCropper.getCroppedCanvas({ width: 800, height: 450, imageSmoothingQuality: 'high' });
    canvas.toBlob(blob => {
        const url = URL.createObjectURL(blob);
        const prev = document.getElementById('coverPreview');
        const ph   = document.getElementById('coverPlaceholder');
        prev.src = url;
        prev.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('coverCropped').value = e.target.result; };
        reader.readAsDataURL(blob);
        albumCropApplied = true;
        bootstrap.Modal.getInstance(document.getElementById('albumCoverCropModal')).hide();
        albumCropper.destroy();
        albumCropper = null;
    }, 'image/jpeg', 0.85);
});

document.getElementById('albumCoverCropModal').addEventListener('hidden.bs.modal', function () {
    if (albumCropper) { albumCropper.destroy(); albumCropper = null; }
    if (!albumCropApplied) {
        document.getElementById('coverCropped').value = '';
        document.getElementById('coverInput').value = '';
    }
    albumCropApplied = false;
});
</script>
<?= $this->endSection() ?>
