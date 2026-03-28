<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/ekskul') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Tambah Ekstrakurikuler</h4>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/ekskul/store') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= esc(old('nama')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pembina</label>
                            <input type="text" class="form-control" name="pembina" value="<?= esc(old('pembina')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jadwal</label>
                            <input type="text" class="form-control" name="jadwal" value="<?= esc(old('jadwal')) ?>" placeholder="Senin 14.00 — 16.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi')) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Prestasi</label>
                            <textarea class="form-control" name="prestasi" rows="2" placeholder="Daftar prestasi yang pernah diraih..."><?= esc(old('prestasi')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom"><i class="bi bi-image me-1 text-primary"></i>Foto & Pengaturan</div>
                <div class="card-body p-3">
                    <div class="mb-3 text-center d-none" id="fotoWrap">
                        <img id="fotoPreview" src="" class="rounded" style="max-height:120px;max-width:100%" alt="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto" id="fotoInput" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $next_urutan ?? 0)) ?>" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                        <label class="form-check-label" for="isActive">Aktif</label>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="<?= base_url('admin/ekskul') ?>" class="btn btn-outline-secondary">Batal</a>
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
    if (file) { const r = new FileReader(); r.onload = e => { document.getElementById('fotoPreview').src = e.target.result; document.getElementById('fotoWrap').classList.remove('d-none'); }; r.readAsDataURL(file); }
});
</script>
<?= $this->endSection() ?>
