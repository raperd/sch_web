<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('admin/aplikasi') ?>" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold mb-0"><?= esc($title) ?></h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php $actionUrl = $app ? base_url('admin/aplikasi/update/'.$app['id']) : base_url('admin/aplikasi/store'); ?>
        <form method="post" action="<?= $actionUrl ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Aplikasi / Link <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama" value="<?= esc(old('nama', $app['nama'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">URL Link <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" name="url" value="<?= esc(old('url', $app['url'] ?? '')) ?>" placeholder="https://..." required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Icon Aplikasi (Opsional)</label>
                    <!-- File input tersembunyi, hanya sebagai pemicu kamera/file picker -->
                    <input type="file" id="iconFileInput" accept="image/*" style="display:none">
                    <!-- Nilai base64 hasil crop yang dikirim ke server -->
                    <input type="hidden" name="icon_cropped" id="iconCropped">
                    <?php if (!empty($app['icon'])): ?>
                        <div class="mb-2" id="iconCurrentWrap">
                            <span class="d-block small text-muted mb-1">Ikon Saat Ini:</span>
                            <?php if (str_starts_with($app['icon'], 'bi-')): ?>
                                <i class="bi <?= esc($app['icon']) ?> fs-3 text-primary"></i>
                            <?php else: ?>
                                <img src="<?= base_url('uploads/aplikasi/' . esc($app['icon'])) ?>" alt="Icon"
                                     width="48" height="48" class="rounded object-fit-cover shadow-sm">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div id="iconPreviewWrap" class="mb-2 d-none">
                        <span class="d-block small text-muted mb-1">Preview Baru:</span>
                        <img id="iconPreviewImg" width="48" height="48" class="rounded object-fit-cover shadow-sm" alt="">
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-1 d-block" id="reCropIconBtn">
                            <i class="bi bi-crop me-1"></i>Ubah Crop
                        </button>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="pickIconBtn">
                            <i class="bi bi-upload me-1"></i>Pilih Icon
                        </button>
                        <span class="text-muted small" id="iconFileName">
                            <?= !empty($app['icon']) ? 'Pilih untuk mengganti' : 'Belum ada icon' ?>
                        </span>
                    </div>
                    <div class="form-text mt-1">Gambar persegi, dipotong otomatis 256×256 px.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $app['urutan'] ?? ($next_urutan ?? 0))) ?>" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Muncul</label>
                    <div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" style="width: 3em; height: 1.5em;" type="checkbox" name="is_active" value="1" <?= old('is_active', $app['is_active'] ?? 1) ? 'checked' : '' ?>>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Deskripsi</label>
                <div class="form-text mt-0 mb-2">Penjelasan mengenai aplikasi/tautan ini (muncul di halaman khusus publik <code>/link-terkait</code>)</div>
                <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi', $app['deskripsi'] ?? '')) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i>Simpan
            </button>
        </form>
    </div>
</div>


<!-- Modal Crop Icon -->
<div class="modal fade" id="iconCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Icon (1:1)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="iconCropImg" src="" alt="Crop" style="max-width:100%;display:block;">
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="iconZoomOut" title="Perkecil"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="iconZoomIn"  title="Perbesar"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="iconRotL"   title="Putar Kiri"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="iconRotR"   title="Putar Kanan"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="iconCropConfirm">
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
document.getElementById('pickIconBtn').addEventListener('click', () => document.getElementById('iconFileInput').click());
document.getElementById('reCropIconBtn').addEventListener('click', () => document.getElementById('iconFileInput').click());

let iconCropper = null, iconCropApplied = false;
const iconModalEl = document.getElementById('iconCropModal');

document.getElementById('iconFileInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const prevFileName = document.getElementById('iconFileName').textContent;
    document.getElementById('iconFileName').textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('iconCropImg').src = e.target.result;
        bootstrap.Modal.getOrCreateInstance(iconModalEl).show();
        iconModalEl.addEventListener('shown.bs.modal', () => {
            if (iconCropper) iconCropper.destroy();
            iconCropper = new Cropper(document.getElementById('iconCropImg'), {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
            });
        }, { once: true });
        iconModalEl.addEventListener('hidden.bs.modal', function () {
            if (iconCropper) { iconCropper.destroy(); iconCropper = null; }
            if (!iconCropApplied) {
                document.getElementById('iconCropped').value = '';
                document.getElementById('iconPreviewWrap').classList.add('d-none');
                document.getElementById('iconFileName').textContent = prevFileName;
                document.getElementById('iconFileInput').value = '';
            }
            iconCropApplied = false;
        }, { once: true });
    };
    reader.readAsDataURL(file);
});

document.getElementById('iconZoomIn').addEventListener('click',  () => iconCropper?.zoom(0.1));
document.getElementById('iconZoomOut').addEventListener('click', () => iconCropper?.zoom(-0.1));
document.getElementById('iconRotL').addEventListener('click',    () => iconCropper?.rotate(-90));
document.getElementById('iconRotR').addEventListener('click',    () => iconCropper?.rotate(90));

document.getElementById('iconCropConfirm').addEventListener('click', function () {
    if (!iconCropper) return;
    const canvas = iconCropper.getCroppedCanvas({ width: 256, height: 256, imageSmoothingQuality: 'high' });
    canvas.toBlob(blob => {
        const url = URL.createObjectURL(blob);
        document.getElementById('iconPreviewImg').src = url;
        document.getElementById('iconPreviewWrap').classList.remove('d-none');
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('iconCropped').value = e.target.result; };
        reader.readAsDataURL(blob);
        iconCropApplied = true;
        bootstrap.Modal.getInstance(iconModalEl).hide();
        iconCropper.destroy();
        iconCropper = null;
    }, 'image/png', 1);
});
</script>
<?= $this->endSection() ?>
