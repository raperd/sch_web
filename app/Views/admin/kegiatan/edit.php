<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/kegiatan') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Kegiatan</h4>
        <p class="text-muted small mb-0"><?= esc(truncate_text($kegiatan['judul'], 60)) ?></p>
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

<form method="post" action="<?= base_url('admin/kegiatan/update/' . $kegiatan['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Main -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" name="judul"
                            value="<?= esc(old('judul', $kegiatan['judul'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi"
                            value="<?= esc(old('lokasi', $kegiatan['lokasi'])) ?>">
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="5"><?= esc(old('deskripsi', $kegiatan['deskripsi'])) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-calendar3 me-1 text-primary"></i>Jadwal
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal"
                                value="<?= esc(old('tanggal', $kegiatan['tanggal'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai <small class="text-muted">(opsional)</small></label>
                            <input type="date" class="form-control" name="tanggal_selesai"
                                value="<?= esc(old('tanggal_selesai', $kegiatan['tanggal_selesai'])) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Status & Tipe -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-sliders me-1 text-primary"></i>Pengaturan
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <?php foreach (['event' => 'Event Sekolah', 'lomba' => 'Lomba / Kompetisi', 'sosial' => 'Bakti Sosial', 'osis' => 'Kegiatan OSIS', 'lainnya' => 'Lainnya'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('tipe', $kegiatan['tipe']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <?php foreach (['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'selesai' => 'Selesai'] as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= old('status', $kegiatan['status']) === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured" value="1"
                                <?= old('is_featured', $kegiatan['is_featured']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isFeatured">
                                <i class="bi bi-star-fill text-warning me-1"></i>Kegiatan Unggulan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= base_url('admin/kegiatan') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>

            <!-- Foto -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-image me-1 text-primary"></i>Foto Kegiatan
                </div>
                <div class="card-body p-3">
                    <?php if (!empty($kegiatan['foto'])): ?>
                        <div class="mb-3 text-center" id="fotoPreviewWrap">
                            <img id="fotoPreview"
                                src="<?= base_url('uploads/kegiatan/' . esc($kegiatan['foto'])) ?>"
                                style="max-height:120px;border-radius:.5rem;max-width:100%" alt="Foto saat ini">
                        </div>
                    <?php else: ?>
                        <div class="mb-3 d-none" id="fotoPreviewWrap">
                            <img id="fotoPreview" src="" style="max-height:120px;border-radius:.5rem" alt="">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="foto" id="fotoInput"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Upload baru untuk mengganti. Maks. 2 MB.</div>
                </div>
            </div>

            <!-- Meta -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <small class="text-muted d-block">
                        <i class="bi bi-calendar3 me-1"></i>Dibuat: <?= format_tanggal($kegiatan['created_at'], 'full') ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('fotoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('fotoPreview').src = e.target.result;
            document.getElementById('fotoPreviewWrap').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
