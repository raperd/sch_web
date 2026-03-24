<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
#quill-editor { min-height: 360px; font-size: 1rem; }
.ql-toolbar.ql-snow { border-radius: .375rem .375rem 0 0; }
.ql-container.ql-snow { border-radius: 0 0 .375rem .375rem; font-size: 1rem; }
.thumb-preview { max-height: 160px; object-fit: cover; border-radius: .5rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/artikel') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Artikel</h4>
        <p class="text-muted small mb-0"><?= esc(truncate_text($artikel['judul'], 60)) ?></p>
    </div>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Periksa kembali:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/artikel/update/' . $artikel['id']) ?>" enctype="multipart/form-data" id="artikelForm">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">

            <!-- Judul -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" name="judul" id="judulInput"
                            value="<?= esc(old('judul', $artikel['judul'])) ?>" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted small"><?= base_url('berita/') ?></span>
                            <input type="text" class="form-control font-monospace" id="slugPreview"
                                value="<?= esc($artikel['slug']) ?>" readonly>
                        </div>
                        <div class="form-text">Slug hanya berubah jika judul diubah.</div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-semibold">Ringkasan / Excerpt</label>
                    <textarea class="form-control" name="ringkasan" rows="3"><?= esc(old('ringkasan', $artikel['ringkasan'])) ?></textarea>
                </div>
            </div>

            <!-- Konten -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                    <div id="quill-editor"></div>
                    <textarea name="konten" id="kontenInput" class="d-none"><?= old('konten', $artikel['konten']) ?></textarea>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Publikasi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-send me-1 text-primary"></i>Publikasi
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" id="statusSelect">
                            <?php foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('status', $artikel['status']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="publishDateGroup">
                        <label class="form-label fw-semibold">Tanggal Publikasi</label>
                        <input type="datetime-local" class="form-control" name="published_at"
                            value="<?= old('published_at', !empty($artikel['published_at']) ? date('Y-m-d\TH:i', strtotime($artikel['published_at'])) : date('Y-m-d\TH:i')) ?>">
                    </div>
                    <div class="mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1"
                                <?= (old('is_featured', $artikel['is_featured']) == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isFeatured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Artikel Pilihan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= base_url('berita/' . esc($artikel['slug'])) ?>" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i>Lihat di Publik
                    </a>
                </div>
            </div>

            <!-- Kategori -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-tag me-1 text-primary"></i>Kategori
                </div>
                <div class="card-body p-3">
                    <select name="kategori_id" class="form-select" required>
                        <option value="">— Pilih Kategori —</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= old('kategori_id', $artikel['kategori_id']) == $k['id'] ? 'selected' : '' ?>>
                                <?= esc($k['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>Thumbnail
                </div>
                <div class="card-body p-3">
                    <?php if (!empty($artikel['thumbnail'])): ?>
                        <div class="mb-2 text-center" id="thumbPreviewWrap">
                            <img id="thumbPreview"
                                src="<?= base_url('uploads/artikel/' . esc($artikel['thumbnail'])) ?>"
                                class="thumb-preview w-100" alt="Thumbnail saat ini">
                            <small class="text-muted d-block mt-1">Thumbnail saat ini</small>
                        </div>
                    <?php else: ?>
                        <div id="thumbPreviewWrap" class="mb-2 text-center d-none">
                            <img id="thumbPreview" src="" class="thumb-preview w-100" alt="Preview">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="thumbnail" id="thumbnailInput"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Upload baru untuk mengganti. JPEG, PNG, WebP. Maks. 2 MB.</div>
                </div>
            </div>

            <!-- Tags -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-tags me-1 text-primary"></i>Tags
                </div>
                <div class="card-body p-3">
                    <input type="text" class="form-control" name="tags"
                        value="<?= esc(old('tags', $artikel['tags'])) ?>"
                        placeholder="berita, kegiatan, prestasi">
                    <div class="form-text">Pisahkan dengan koma.</div>
                </div>
            </div>

            <!-- Meta -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <small class="text-muted d-block"><i class="bi bi-calendar3 me-1"></i>Dibuat: <?= format_tanggal($artikel['created_at'], 'full') ?></small>
                    <small class="text-muted d-block mt-1"><i class="bi bi-eye me-1"></i><?= number_format($artikel['view_count'] ?? 0) ?> kali dibaca</small>
                </div>
            </div>

        </div>
    </div>

</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
// Init Quill dengan konten yang ada
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [2, 3, 4, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            [{ align: [] }],
            ['clean']
        ]
    }
});

// Set existing content
const existing = document.getElementById('kontenInput').value;
if (existing) {
    quill.root.innerHTML = existing;
}

// Sync ke hidden textarea saat submit
document.getElementById('artikelForm').addEventListener('submit', function() {
    document.getElementById('kontenInput').value = quill.root.innerHTML;
});

// Auto-update slug preview jika judul berubah
const origJudul = <?= json_encode($artikel['judul']) ?>;
const origSlug  = <?= json_encode($artikel['slug']) ?>;
function toSlug(str) {
    return str.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
}
document.getElementById('judulInput').addEventListener('input', function() {
    if (this.value !== origJudul) {
        document.getElementById('slugPreview').value = toSlug(this.value) + ' (baru)';
    } else {
        document.getElementById('slugPreview').value = origSlug;
    }
});

// Toggle publish date visibility
function togglePublishDate() {
    const v = document.getElementById('statusSelect').value;
    document.getElementById('publishDateGroup').style.display = v === 'published' ? '' : 'none';
}
document.getElementById('statusSelect').addEventListener('change', togglePublishDate);
togglePublishDate();

// Thumbnail preview
document.getElementById('thumbnailInput').addEventListener('change', function() {
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
