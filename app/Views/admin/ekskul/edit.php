<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= admin_url('ekskul') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Ekstrakurikuler</h4>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= admin_url('ekskul/update/' . $ekskul['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= esc(old('nama', $ekskul['nama'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pembina</label>
                            <input type="text" class="form-control" name="pembina" value="<?= esc(old('pembina', $ekskul['pembina'])) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jadwal</label>
                            <input type="text" class="form-control" name="jadwal" value="<?= esc(old('jadwal', $ekskul['jadwal'])) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi', $ekskul['deskripsi'])) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Prestasi</label>
                            <textarea class="form-control" name="prestasi" rows="2"><?= esc(old('prestasi', $ekskul['prestasi'])) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom"><i class="bi bi-image me-1 text-primary"></i>Foto & Pengaturan</div>
                <div class="card-body p-3">
                    <div class="mb-2 text-center <?= empty($ekskul['foto']) ? 'd-none' : '' ?>" id="fotoWrap">
                        <img id="fotoPreview"
                            src="<?= !empty($ekskul['foto']) ? base_url('uploads/ekskul/' . esc($ekskul['foto'])) : '' ?>"
                            class="rounded" style="max-height:120px;max-width:100%" alt="">
                        <button type="button" class="btn btn-sm btn-outline-secondary d-block mx-auto mt-2" id="reCropBtn">
                            <i class="bi bi-crop me-1"></i>Ubah Crop
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto</label>
                        <input type="file" id="fotoInput" accept="image/jpeg,image/png,image/webp" style="display:none">
                        <input type="hidden" name="foto_cropped" id="fotoCropped">
                        <div class="d-flex gap-2 align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="pickFotoBtn">
                                <i class="bi bi-upload me-1"></i>Ganti Foto
                            </button>
                            <span class="text-muted small" id="fotoFileName">
                                <?= !empty($ekskul['foto']) ? esc($ekskul['foto']) : 'Belum ada foto' ?>
                            </span>
                        </div>
                        <div class="form-text">JPEG/PNG. Dipotong otomatis rasio 16:9.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $ekskul['urutan'])) ?>" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" <?= $ekskul['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Aktif</label>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="<?= admin_url('ekskul') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Crop Foto Ekskul -->
<div class="modal fade" id="ekskulCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Foto (16:9)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="ekskulCropImg" src="" alt="Crop" style="max-width:100%;display:block;">
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="eksZoomOut"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="eksZoomIn"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="eksRotL"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="eksRotR"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="ekskulCropConfirm">
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
    let eksCropper = null,
        eksCropApplied = false;
    const eksModalEl = document.getElementById('ekskulCropModal');
    const fotoWrap = document.getElementById('fotoWrap');

    document.getElementById('pickFotoBtn').addEventListener('click', () => document.getElementById('fotoInput').click());
    document.getElementById('reCropBtn').addEventListener('click', () => document.getElementById('fotoInput').click());

    document.getElementById('fotoInput').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const wasVisible = !fotoWrap.classList.contains('d-none');
        const prevSrc = document.getElementById('fotoPreview').src;

        document.getElementById('fotoFileName').textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('ekskulCropImg').src = e.target.result;
            bootstrap.Modal.getOrCreateInstance(eksModalEl).show();
            eksModalEl.addEventListener('shown.bs.modal', () => {
                if (eksCropper) eksCropper.destroy();
                eksCropper = new Cropper(document.getElementById('ekskulCropImg'), {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                });
            }, {
                once: true
            });

            eksModalEl.addEventListener('hidden.bs.modal', function() {
                if (eksCropper) {
                    eksCropper.destroy();
                    eksCropper = null;
                }
                if (!eksCropApplied) {
                    document.getElementById('fotoCropped').value = '';
                    document.getElementById('fotoInput').value = '';
                    document.getElementById('fotoFileName').textContent = wasVisible ? prevSrc.split('/').pop() : 'Belum ada foto';
                    if (wasVisible) {
                        document.getElementById('fotoPreview').src = prevSrc;
                        fotoWrap.classList.remove('d-none');
                    } else {
                        fotoWrap.classList.add('d-none');
                    }
                }
                eksCropApplied = false;
            }, {
                once: true
            });
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('eksZoomIn').addEventListener('click', () => eksCropper?.zoom(0.1));
    document.getElementById('eksZoomOut').addEventListener('click', () => eksCropper?.zoom(-0.1));
    document.getElementById('eksRotL').addEventListener('click', () => eksCropper?.rotate(-90));
    document.getElementById('eksRotR').addEventListener('click', () => eksCropper?.rotate(90));

    document.getElementById('ekskulCropConfirm').addEventListener('click', function() {
        if (!eksCropper) return;
        eksCropper.getCroppedCanvas({
            width: 1200,
            height: 675,
            imageSmoothingQuality: 'high'
        }).toBlob(blob => {
            const url = URL.createObjectURL(blob);
            document.getElementById('fotoPreview').src = url;
            fotoWrap.classList.remove('d-none');
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('fotoCropped').value = e.target.result;
            };
            reader.readAsDataURL(blob);
            eksCropApplied = true;
            bootstrap.Modal.getInstance(eksModalEl).hide();
            eksCropper.destroy();
            eksCropper = null;
        }, 'image/jpeg', 0.85);
    });
</script>
<?= $this->endSection() ?>