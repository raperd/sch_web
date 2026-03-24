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
                    <input type="file" class="form-control" name="file_path" id="mainFileInput"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Kosongkan jika tidak ingin mengganti. Maks. 2 MB.</div>
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
                    <input type="file" class="form-control" name="thumbnail" id="thumbInput"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Upload baru untuk mengganti.</div>
                </div>
            </div>

        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('thumbInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('thumbPreview').src = e.target.result;
            document.getElementById('thumbPreviewWrap').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
