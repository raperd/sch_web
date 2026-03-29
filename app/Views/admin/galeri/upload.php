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
                            <p class="mb-1 text-muted">Klik atau drag &amp; drop file di sini</p>
                            <small class="text-muted">JPEG, PNG, WebP. Maks. 2 MB. Akan di-crop 16:9.</small>
                        </div>
                        <div id="filePreviewWrap" class="d-none">
                            <img id="filePreview" src="" style="max-height:200px;border-radius:.5rem" alt="">
                            <p id="fileName" class="mt-2 mb-0 text-muted small"></p>
                        </div>
                    </div>
                    <input type="file" class="d-none crop-input" name="file_path" id="mainFileInput"
                        accept="image/jpeg,image/png,image/webp"
                        data-aspect-ratio="1.778"
                        required>
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
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $next_urutan ?? 0)) ?>" min="0">
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
                    <input type="file" class="form-control crop-input" name="thumbnail" id="thumbInput"
                        accept="image/jpeg,image/png,image/webp"
                        data-aspect-ratio="1.778">
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

<!-- Modal Crop -->
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
let galeriCropApplied = false;

document.querySelectorAll('.crop-input').forEach(input => {
    input.addEventListener('change', function () {
        if (!this.files[0]) return;
        activeCropInput = this;
        const ratio = parseFloat(this.dataset.aspectRatio || 1);
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('cropImage').src = e.target.result;
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cropModal'));
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

        // Update preview
        const wrap = activeCropInput.closest('.col-lg-8, .col-lg-4, .card-body');
        if (activeCropInput.id === 'mainFileInput') {
            document.getElementById('filePreview').src = URL.createObjectURL(blob);
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('dropZoneContent').classList.add('d-none');
            document.getElementById('filePreviewWrap').classList.remove('d-none');
        } else if (activeCropInput.id === 'thumbInput') {
            document.getElementById('thumbPreview').src = URL.createObjectURL(blob);
            document.getElementById('thumbPreviewWrap').classList.remove('d-none');
        }
        galeriCropApplied = true;
        bootstrap.Modal.getInstance(document.getElementById('cropModal'))?.hide();
    }, 'image/jpeg', 0.88);
});

document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
    if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
    if (!galeriCropApplied && activeCropInput) {
        activeCropInput.value = '';
        if (activeCropInput.id === 'mainFileInput') {
            document.getElementById('filePreviewWrap').classList.add('d-none');
            document.getElementById('dropZoneContent').classList.remove('d-none');
        } else if (activeCropInput.id === 'thumbInput') {
            document.getElementById('thumbPreviewWrap').classList.add('d-none');
        }
    }
    galeriCropApplied = false;
});

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.borderColor = '#dee2e6';
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const mainInput = document.getElementById('mainFileInput');
        mainInput.files = dt.files;
        // Trigger crop flow
        mainInput.dispatchEvent(new Event('change'));
    }
}
</script>
<?= $this->endSection() ?>
