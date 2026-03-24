<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/galeri') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Upload Foto / Video</h4>
        <p class="text-muted small mb-0">Tambah media baru ke galeri sekolah</p>
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

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= esc(session('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/galeri/store') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">

            <!-- Upload Area -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-cloud-upload me-1 text-primary"></i>File Utama
                </div>
                <div class="card-body p-4">
                    <!-- Drop zone visual -->
                    <div id="dropZone" class="border border-2 border-dashed rounded-3 p-5 text-center mb-3"
                        style="border-color: #dee2e6 !important; cursor:pointer; transition: border-color .2s"
                        onclick="document.getElementById('mainFileInput').click()"
                        ondragover="event.preventDefault(); this.style.borderColor='#0d6efd'"
                        ondragleave="this.style.borderColor='#dee2e6'"
                        ondrop="handleDrop(event)">
                        <div id="dropZoneContent">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted d-block mb-2"></i>
                            <p class="mb-1 text-muted">Klik atau drag & drop file di sini</p>
                            <small class="text-muted">JPEG, PNG, WebP. Maks. 2 MB.</small>
                        </div>
                        <div id="filePreviewWrap" class="d-none">
                            <img id="filePreview" src="" style="max-height:200px;border-radius:.5rem" alt="">
                            <p id="fileName" class="mt-2 mb-0 text-muted small"></p>
                        </div>
                    </div>
                    <input type="file" class="d-none" name="file_path" id="mainFileInput"
                        accept="image/jpeg,image/png,image/webp" required>
                </div>
            </div>

            <!-- Judul & Deskripsi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul" value="<?= esc(old('judul')) ?>" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi')) ?></textarea>
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
                                <option value="<?= $k['id'] ?>" <?= old('kategori_id') == $k['id'] ? 'selected' : '' ?>>
                                    <?= esc($k['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <option value="foto"  <?= old('tipe') === 'foto'  ? 'selected' : '' ?>>Foto</option>
                            <option value="video" <?= old('tipe') === 'video' ? 'selected' : '' ?>>Video (thumbnail)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', 0)) ?>" min="0">
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1">
                            <label class="form-check-label" for="isFeatured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Foto Unggulan
                            </label>
                        </div>
                        <div class="form-text">Tampil di galeri sorotan halaman Profil.</div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail opsional -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>Thumbnail (Opsional)
                </div>
                <div class="card-body p-3">
                    <div id="thumbPreviewWrap" class="mb-2 text-center d-none">
                        <img id="thumbPreview" src="" style="max-height:100px;border-radius:.5rem" alt="">
                    </div>
                    <input type="file" class="form-control" name="thumbnail" id="thumbInput"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Jika kosong, file utama digunakan sebagai thumbnail.</div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                    <i class="bi bi-cloud-upload me-1"></i>Upload Foto
                </button>
                <a href="<?= base_url('admin/galeri') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const mainInput = document.getElementById('mainFileInput');
const previewWrap = document.getElementById('filePreviewWrap');
const dropContent = document.getElementById('dropZoneContent');

mainInput.addEventListener('change', function() { previewFile(this.files[0]); });

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.borderColor = '#dee2e6';
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        mainInput.files = dt.files;
        previewFile(file);
    }
}

function previewFile(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('filePreview').src = e.target.result;
        document.getElementById('fileName').textContent = file.name;
        dropContent.classList.add('d-none');
        previewWrap.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

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
