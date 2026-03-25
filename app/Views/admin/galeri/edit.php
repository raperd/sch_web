<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/galeri') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Galeri</h4>
        <p class="text-muted small mb-0"><?= esc(truncate_text($galeri['judul'], 60)) ?></p>
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

<form method="post" action="<?= base_url('admin/galeri/update/' . $galeri['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">

            <!-- Foto Saat Ini -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>File Foto/Video
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($galeri['file_path'])): ?>
                        <div class="mb-3 text-center">
                            <img src="<?= base_url('uploads/galeri/' . esc($galeri['file_path'])) ?>"
                                style="max-height:200px;border-radius:.5rem;max-width:100%" alt="Foto saat ini">
                            <small class="text-muted d-block mt-1">File saat ini: <code><?= esc($galeri['file_path']) ?></code></small>
                        </div>
                    <?php endif; ?>
                    <label class="form-label">Ganti File (opsional)</label>
                    <input type="file" class="form-control crop-input" name="file_path" id="mainFileInput"
                        accept="image/jpeg,image/png,image/webp"
                        data-aspect-ratio="1.778">
                    <div class="form-text">Kosongkan jika tidak ingin mengganti. Maks. 2 MB. Akan di-crop 16:9.</div>
                    <div id="newFilePreviewWrap" class="mt-2 text-center d-none">
                        <img id="newFilePreview" src="" style="max-height:120px;border-radius:.5rem" alt="">
                        <p id="newFileName" class="mt-1 mb-0 text-muted small"></p>
                    </div>
                </div>
            </div>

            <!-- Judul & Deskripsi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul" value="<?= esc(old('judul', $galeri['judul'])) ?>" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi', $galeri['deskripsi'])) ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Metadata -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-tag me-1 text-primary"></i>Metadata
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= old('kategori_id', $galeri['kategori_id']) == $k['id'] ? 'selected' : '' ?>>
                                    <?= esc($k['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <select name="tipe" class="form-select">
                            <option value="foto"  <?= old('tipe', $galeri['tipe']) === 'foto'  ? 'selected' : '' ?>>Foto</option>
                            <option value="video" <?= old('tipe', $galeri['tipe']) === 'video' ? 'selected' : '' ?>>Video</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan"
                            value="<?= esc(old('urutan', $galeri['urutan'])) ?>" min="0">
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1"
                                <?= old('is_featured', $galeri['is_featured']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isFeatured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Foto Unggulan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= base_url('admin/galeri') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>Thumbnail (Opsional)
                </div>
                <div class="card-body p-3">
                    <?php if (!empty($galeri['thumbnail'])): ?>
                        <div class="mb-2 text-center" id="thumbPreviewWrap">
                            <img id="thumbPreview"
                                src="<?= base_url('uploads/galeri/' . esc($galeri['thumbnail'])) ?>"
                                style="max-height:80px;border-radius:.5rem" alt="Thumbnail">
                        </div>
                    <?php else: ?>
                        <div class="mb-2 d-none" id="thumbPreviewWrap">
                            <img id="thumbPreview" src="" style="max-height:80px;border-radius:.5rem" alt="">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control crop-input" name="thumbnail" id="thumbInput"
                        accept="image/jpeg,image/png,image/webp"
                        data-aspect-ratio="1.778">
                    <div class="form-text">Upload baru untuk mengganti.</div>
                </div>
            </div>

        </div>
    </div>
</form>

<!-- Modal Crop -->
<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-dark p-3">
                <img id="cropImage" src="" style="max-width:100%;max-height:450px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="cropConfirm">
                    <i class="bi bi-check-lg me-1"></i>Crop &amp; Gunakan
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
let cropperInstance = null;
let activeCropInput = null;

document.querySelectorAll('.crop-input').forEach(input => {
    input.addEventListener('change', function () {
        if (!this.files[0]) return;
        activeCropInput = this;
        const ratio = parseFloat(this.dataset.aspectRatio || 1);
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('cropImage').src = e.target.result;
            const modal = new bootstrap.Modal(document.getElementById('cropModal'));
            modal.show();
            document.getElementById('cropModal').addEventListener('shown.bs.modal', () => {
                if (cropperInstance) cropperInstance.destroy();
                cropperInstance = new Cropper(document.getElementById('cropImage'), {
                    aspectRatio: ratio,
                    viewMode: 1,
                    autoCropArea: 0.9,
                });
            }, { once: true });
        };
        reader.readAsDataURL(this.files[0]);
    });
});

document.getElementById('cropConfirm')?.addEventListener('click', function () {
    if (!cropperInstance || !activeCropInput) return;
    const ratio = parseFloat(activeCropInput.dataset.aspectRatio || 1);
    const w = ratio >= 1 ? 800 : 600;
    const h = Math.round(w / ratio);
    cropperInstance.getCroppedCanvas({ width: w, height: h }).toBlob(blob => {
        const origName = activeCropInput.files[0] ? activeCropInput.files[0].name : 'foto-crop.jpg';
        const file = new File([blob], origName.replace(/\.[^.]+$/, '-crop.jpg'), { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        activeCropInput.files = dt.files;

        if (activeCropInput.id === 'mainFileInput') {
            document.getElementById('newFilePreview').src = URL.createObjectURL(blob);
            document.getElementById('newFileName').textContent = file.name;
            document.getElementById('newFilePreviewWrap').classList.remove('d-none');
        } else if (activeCropInput.id === 'thumbInput') {
            document.getElementById('thumbPreview').src = URL.createObjectURL(blob);
            document.getElementById('thumbPreviewWrap').classList.remove('d-none');
        }
        bootstrap.Modal.getInstance(document.getElementById('cropModal'))?.hide();
    }, 'image/jpeg', 0.88);
});
</script>
<?= $this->endSection() ?>
