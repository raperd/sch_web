<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/fasilitas') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Tambah Fasilitas</h4>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/fasilitas/store') ?>" enctype="multipart/form-data">
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
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto" id="fotoInput" accept="image/*">
                    </div>
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $next_urutan ?? 0)) ?>" min="0">
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="<?= base_url('admin/fasilitas') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.getElementById('iconInput').addEventListener('input', function () {
    document.getElementById('iconPreview').className = 'bi ' + this.value;
});
document.getElementById('fotoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    this.value = '';
    AppCrop.open(file, this, {
        bw: 320, bh: 240, ow: 800, oh: 600,
        onDone(blob, url) {
            document.getElementById('fotoPreview').src = url;
            document.getElementById('fotoWrap').classList.remove('d-none');
        }
    });
});
</script>
<?= $this->endSection() ?>
