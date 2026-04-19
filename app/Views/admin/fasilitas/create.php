<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= admin_url('fasilitas') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Tambah Fasilitas</h4>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= admin_url('fasilitas/store') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama Fasilitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= esc(old('nama')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Bootstrap Icons</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-grid" id="iconPreview"></i></span>
                                <input type="text" class="form-control" name="icon" id="iconInput"
                                    value="<?= esc(old('icon', 'bi-building')) ?>"
                                    placeholder="bi-building">
                            </div>
                            <div class="form-text">Lihat: <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" value="<?= esc(old('jumlah', 1)) ?>" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="baik" <?= old('kondisi', 'baik') === 'baik' ? 'selected' : '' ?>>Baik</option>
                                <option value="rusak_ringan" <?= old('kondisi') === 'rusak_ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                                <option value="rusak_berat" <?= old('kondisi') === 'rusak_berat' ? 'selected' : '' ?>>Rusak Berat</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom"><i class="bi bi-image me-1 text-primary"></i>Foto & Urutan</div>
                <div class="card-body p-3">
                    <div class="mb-3 text-center d-none" id="fotoWrap">
                        <img id="fotoPreview" src="" class="rounded" style="max-height:120px;max-width:100%" alt="">
                        <button type="button" class="btn btn-sm btn-outline-secondary d-block mx-auto mt-2" id="reCropFotoBtn">
                            <i class="bi bi-crop me-1"></i>Ubah Crop
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <!-- File input tersembunyi, hanya sebagai pemicu kamera/file picker -->
                        <input type="file" id="fotoInput" accept="image/*" style="display:none">
                        <!-- Nilai base64 hasil crop yang dikirim ke server -->
                        <input type="hidden" name="foto_cropped" id="fotoCropped">
                        <div class="d-flex gap-2 align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="pickFotoBtn">
                                <i class="bi bi-upload me-1"></i>Pilih Foto
                            </button>
                            <span class="text-muted small" id="fotoFileName">Belum ada foto dipilih</span>
                        </div>
                        <div class="form-text">JPEG/PNG. Foto akan dipotong otomatis rasio 16:9.</div>
                    </div>
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $next_urutan ?? 0)) ?>" min="0">
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="<?= admin_url('fasilitas') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Modal Crop Foto -->
<div class="modal fade" id="fasilitasCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Foto (16:9)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="fasilitasCropImg" src="" alt="Crop" style="max-width:100%;display:block;">
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fasZoomOut" title="Perkecil"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fasZoomIn" title="Perbesar"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fasRotL" title="Putar Kiri"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fasRotR" title="Putar Kanan"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="fasilitasCropConfirm">
                        <i class="bi bi-check-lg me-1"></i>Crop &amp; Gunakan
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
    document.getElementById('iconInput').addEventListener('input', function() {
        document.getElementById('iconPreview').className = 'bi ' + this.value;
    });
    document.getElementById('pickFotoBtn').addEventListener('click', () => document.getElementById('fotoInput').click());
    document.getElementById('reCropFotoBtn').addEventListener('click', () => document.getElementById('fotoInput').click());

    let fasCropper = null,
        fasCropApplied = false;
    const fasModalEl = document.getElementById('fasilitasCropModal');

    document.getElementById('fotoInput').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        document.getElementById('fotoFileName').textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('fasilitasCropImg').src = e.target.result;
            bootstrap.Modal.getOrCreateInstance(fasModalEl).show();
            fasModalEl.addEventListener('shown.bs.modal', () => {
                if (fasCropper) fasCropper.destroy();
                fasCropper = new Cropper(document.getElementById('fasilitasCropImg'), {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                });
            }, {
                once: true
            });
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('fasZoomIn').addEventListener('click', () => fasCropper?.zoom(0.1));
    document.getElementById('fasZoomOut').addEventListener('click', () => fasCropper?.zoom(-0.1));
    document.getElementById('fasRotL').addEventListener('click', () => fasCropper?.rotate(-90));
    document.getElementById('fasRotR').addEventListener('click', () => fasCropper?.rotate(90));

    document.getElementById('fasilitasCropConfirm').addEventListener('click', function() {
        if (!fasCropper) return;
        const canvas = fasCropper.getCroppedCanvas({
            width: 1200,
            height: 675,
            imageSmoothingQuality: 'high'
        });
        canvas.toBlob(blob => {
            const url = URL.createObjectURL(blob);
            document.getElementById('fotoPreview').src = url;
            document.getElementById('fotoWrap').classList.remove('d-none');
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('fotoCropped').value = e.target.result;
            };
            reader.readAsDataURL(blob);
            fasCropApplied = true;
            bootstrap.Modal.getInstance(fasModalEl).hide();
            fasCropper.destroy();
            fasCropper = null;
        }, 'image/jpeg', 0.85);
    });

    fasModalEl.addEventListener('hidden.bs.modal', function() {
        if (fasCropper) {
            fasCropper.destroy();
            fasCropper = null;
        }
        if (!fasCropApplied) {
            document.getElementById('fotoCropped').value = '';
            document.getElementById('fotoWrap').classList.add('d-none');
            document.getElementById('fotoFileName').textContent = 'Belum ada foto dipilih';
            document.getElementById('fotoInput').value = '';
        }
        fasCropApplied = false;
    });
</script>
<?= $this->endSection() ?>