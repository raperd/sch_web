<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/guru') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Guru / Staf</h4>
        <p class="text-muted small mb-0"><?= esc($guru['nama']) ?></p>
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

<form method="post" action="<?= base_url('admin/guru/update/' . $guru['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">Data Identitas</div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= esc(old('nama', $guru['nama'])) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" name="nip" value="<?= esc(old('nip', $guru['nip'])) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jabatan" value="<?= esc(old('jabatan', $guru['jabatan'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" class="form-control" name="pendidikan" value="<?= esc(old('pendidikan', $guru['pendidikan'])) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" class="form-control" name="mata_pelajaran" value="<?= esc(old('mata_pelajaran', $guru['mata_pelajaran'])) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Publik</label>
                            <input type="email" class="form-control" name="email_publik" value="<?= esc(old('email_publik', $guru['email_publik'])) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Filosofi Mengajar</label>
                            <textarea class="form-control" name="filosofi_mengajar" rows="3"><?= esc(old('filosofi_mengajar', $guru['filosofi_mengajar'])) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Foto -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-person-circle me-1 text-primary"></i>Foto
                </div>
                <div class="card-body p-3 text-center">
                    <?php if (!empty($guru['foto'])): ?>
                        <div class="mb-3" id="fotoPreviewWrap">
                            <img id="fotoPreview"
                                src="<?= base_url('uploads/guru/' . esc($guru['foto'])) ?>"
                                class="rounded-circle"
                                style="width:100px;height:100px;object-fit:cover" alt="">
                        </div>
                    <?php else: ?>
                        <div class="mb-3 d-none" id="fotoPreviewWrap">
                            <img id="fotoPreview" src="" class="rounded-circle"
                                style="width:100px;height:100px;object-fit:cover" alt="">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control crop-input" name="foto" id="fotoInput"
                        accept="image/jpeg,image/png,image/webp"
                        data-aspect-ratio="1">
                    <div class="form-text">Upload baru untuk mengganti. Maks. 2 MB.</div>
                </div>
            </div>

            <!-- Klasifikasi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-tag me-1 text-primary"></i>Klasifikasi
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <?php foreach (['guru' => 'Guru', 'staf' => 'Staf', 'tendik' => 'Tendik'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('tipe', $guru['tipe']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang</label>
                        <select name="bidang_id" class="form-select">
                            <option value="">— Pilih Bidang —</option>
                            <?php foreach ($bidang as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= old('bidang_id', $guru['bidang_id']) == $b['id'] ? 'selected' : '' ?>>
                                    <?= esc($b['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan"
                            value="<?= esc(old('urutan', $guru['urutan'])) ?>" min="0">
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1"
                                <?= old('is_active', $guru['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Aktif / Tampilkan</label>
                        </div>
                    </div>
                    <div id="periodeWrapper" class="mt-2"
                         style="<?= old('is_active', $guru['is_active']) ? 'display:none' : '' ?>">
                        <label class="form-label fw-semibold small mb-1">Periode Bertugas</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="tahun_masuk"
                                       class="form-control form-control-sm"
                                       min="1945" max="2099" placeholder="Tahun masuk"
                                       value="<?= esc(old('tahun_masuk', $guru['tahun_masuk'] ?? '')) ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="tahun_keluar"
                                       class="form-control form-control-sm"
                                       min="1945" max="2099" placeholder="Tahun keluar"
                                       value="<?= esc(old('tahun_keluar', $guru['tahun_keluar'] ?? '')) ?>">
                            </div>
                        </div>
                        <div class="form-text">Tampil di tab "Masa ke Masa" halaman Direktori.</div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= base_url('admin/guru') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>

            <!-- Meta -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <small class="text-muted d-block"><i class="bi bi-calendar3 me-1"></i>Ditambah: <?= format_tanggal($guru['created_at'], 'full') ?></small>
                </div>
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
let guruCropApplied = false;

document.querySelectorAll('.crop-input').forEach(input => {
    input.addEventListener('change', function () {
        if (!this.files[0]) return;
        activeCropInput = this;
        const ratio = parseFloat(this.dataset.aspectRatio || 1);
        const previewWrap = document.getElementById('fotoPreviewWrap');
        const wasVisible  = !previewWrap.classList.contains('d-none');
        const prevSrc     = document.getElementById('fotoPreview')?.src || '';
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
            document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
                if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
                if (!guruCropApplied && activeCropInput) {
                    activeCropInput.value = '';
                    if (wasVisible) {
                        // Restore existing photo preview — do NOT hide
                        document.getElementById('fotoPreview').src = prevSrc;
                    } else {
                        previewWrap.classList.add('d-none');
                    }
                }
                guruCropApplied = false;
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
        const file = new File([blob], 'foto-crop.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        activeCropInput.files = dt.files;
        // Update preview
        const prev = document.getElementById('fotoPreview');
        if (prev) {
            prev.src = URL.createObjectURL(blob);
            document.getElementById('fotoPreviewWrap').classList.remove('d-none');
        }
        guruCropApplied = true;
        bootstrap.Modal.getInstance(document.getElementById('cropModal'))?.hide();
    }, 'image/jpeg', 0.88);
});

// Toggle periode bertugas berdasarkan status aktif
(function () {
    const chk  = document.getElementById('isActive');
    const wrap = document.getElementById('periodeWrapper');
    const sync = () => { wrap.style.display = chk.checked ? 'none' : ''; };
    chk.addEventListener('change', sync);
    sync();
}());
</script>
<?= $this->endSection() ?>
