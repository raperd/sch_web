<?= $this->extend('layouts/admin') ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">
<style>
#cropImageKepsek { max-width: 100%; display: block; }
.foto-preview { width: 140px; height: 140px; object-fit: cover; border-radius: 50%; border: 3px solid var(--bs-primary); }
.foto-placeholder { width: 140px; height: 140px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; border: 3px dashed #ced4da; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/kepala-sekolah') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><?= $item ? 'Edit' : 'Tambah' ?> Kepala Sekolah</h4>
        <small class="text-muted">Lengkapi data kepala sekolah</small>
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

<?php $action = $item
    ? base_url('admin/kepala-sekolah/update/' . $item['id'])
    : base_url('admin/kepala-sekolah/store'); ?>

<form method="POST" action="<?= $action ?>" enctype="multipart/form-data" id="kepsekForm">
    <?= csrf_field() ?>
    <input type="hidden" name="foto_cropped" id="fotoCropped">

    <div class="row g-4">
        <!-- Foto -->
        <div class="col-lg-3 text-center">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center gap-3">
                    <label class="form-label fw-semibold w-100 text-start">Foto</label>

                    <div id="fotoPreviewWrap">
                        <?php if (!empty($item['foto'])): ?>
                            <img id="fotoPreview"
                                src="<?= base_url('uploads/kepala_sekolah/' . esc($item['foto'])) ?>"
                                class="foto-preview" alt="Foto">
                        <?php else: ?>
                            <div class="foto-placeholder" id="fotoPlaceholder">
                                <i class="bi bi-person fs-1 text-muted"></i>
                            </div>
                            <img id="fotoPreview" src="" class="foto-preview d-none" alt="Foto">
                        <?php endif; ?>
                    </div>

                    <input type="file" name="foto" id="fotoInput"
                        accept="image/jpeg,image/png,image/webp" style="display:none">

                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="pickFotoBtn">
                        <i class="bi bi-upload me-1"></i>
                        <?= !empty($item['foto']) ? 'Ganti Foto' : 'Pilih Foto' ?>
                    </button>
                    <div class="form-text text-center">Foto resmi 1:1 (persegi). <br>Disarankan 400×400px.</div>
                </div>
            </div>
        </div>

        <!-- Data -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gelar Depan</label>
                            <input type="text" name="gelar_depan" class="form-control"
                                value="<?= esc(old('gelar_depan', $item['gelar_depan'] ?? '')) ?>"
                                placeholder="Dr., Drs., H.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required maxlength="150"
                                value="<?= esc(old('nama', $item['nama'] ?? '')) ?>"
                                placeholder="Nama tanpa gelar">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" class="form-control"
                                value="<?= esc(old('gelar_belakang', $item['gelar_belakang'] ?? '')) ?>"
                                placeholder="M.Pd., S.Pd.">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tahun Mulai <span class="text-danger">*</span></label>
                            <input type="number" name="periode_mulai" class="form-control" required
                                min="1900" max="2100"
                                value="<?= esc(old('periode_mulai', $item['periode_mulai'] ?? date('Y'))) ?>"
                                placeholder="2020">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tahun Selesai</label>
                            <input type="number" name="periode_selesai" class="form-control" id="periodeSelesai"
                                min="1900" max="2100"
                                value="<?= esc(old('periode_selesai', $item['periode_selesai'] ?? '')) ?>"
                                placeholder="Kosongkan jika masih menjabat">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="masihMenjabat"
                                    <?= empty($item['periode_selesai']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="masihMenjabat">
                                    <span class="badge text-bg-success">Menjabat Saat Ini</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                placeholder="Pencapaian, latar belakang, atau informasi singkat lainnya..."><?= esc(old('keterangan', $item['keterangan'] ?? '')) ?></textarea>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control" min="0"
                                value="<?= esc(old('urutan', $item['urutan'] ?? ($next_urutan ?? 0))) ?>">
                            <div class="form-text">0 = otomatis urut dari periode terlama.</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                    <a href="<?= base_url('admin/kepala-sekolah') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>
                        <?= $item ? 'Simpan Perubahan' : 'Tambah Data' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Crop Foto -->
<div class="modal fade" id="fotoKepsekCropModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Foto (1:1)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark p-2" style="max-height:60vh;overflow:auto;">
                <img id="cropImageKepsek" src="" alt="Crop">
            </div>
            <div class="modal-footer justify-content-between">
                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Foto akan di-crop menjadi persegi 1:1.</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="kepsekCropConfirm">
                        <i class="bi bi-check-lg me-1"></i>Terapkan
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
// Toggle tahun selesai
document.getElementById('masihMenjabat').addEventListener('change', function() {
    const input = document.getElementById('periodeSelesai');
    if (this.checked) {
        input.value = '';
        input.disabled = true;
    } else {
        input.disabled = false;
        input.focus();
    }
});
// Inisialisasi state checkbox
(function() {
    const chk = document.getElementById('masihMenjabat');
    document.getElementById('periodeSelesai').disabled = chk.checked;
})();

// ===== FOTO CROP =====
let kepsekCropper = null;

document.getElementById('pickFotoBtn').addEventListener('click', () => {
    document.getElementById('fotoInput').click();
});

document.getElementById('fotoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('cropImageKepsek').src = e.target.result;
        const modal = new bootstrap.Modal(document.getElementById('fotoKepsekCropModal'));
        modal.show();
        document.getElementById('fotoKepsekCropModal').addEventListener('shown.bs.modal', () => {
            if (kepsekCropper) kepsekCropper.destroy();
            kepsekCropper = new Cropper(document.getElementById('cropImageKepsek'), {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
            });
        }, { once: true });
    };
    reader.readAsDataURL(file);
});

document.getElementById('kepsekCropConfirm').addEventListener('click', function() {
    if (!kepsekCropper) return;
    const canvas = kepsekCropper.getCroppedCanvas({ width: 400, height: 400, imageSmoothingQuality: 'high' });
    canvas.toBlob(blob => {
        const url = URL.createObjectURL(blob);
        // Tampilkan preview
        const prev = document.getElementById('fotoPreview');
        const ph   = document.getElementById('fotoPlaceholder');
        prev.src = url;
        prev.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
        // Simpan base64 ke hidden input
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('fotoCropped').value = e.target.result; };
        reader.readAsDataURL(blob);
        bootstrap.Modal.getInstance(document.getElementById('fotoKepsekCropModal')).hide();
        kepsekCropper.destroy(); kepsekCropper = null;
    }, 'image/jpeg', 0.88);
});
</script>
<?= $this->endSection() ?>
