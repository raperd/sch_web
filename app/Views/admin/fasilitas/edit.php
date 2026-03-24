<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/fasilitas') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Fasilitas</h4>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/fasilitas/update/' . $fasilitas['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama Fasilitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= esc(old('nama', $fasilitas['nama'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Bootstrap Icons</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi <?= esc($fasilitas['icon']) ?>" id="iconPreview"></i></span>
                                <input type="text" class="form-control" name="icon" id="iconInput"
                                    value="<?= esc(old('icon', $fasilitas['icon'])) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" value="<?= esc(old('jumlah', $fasilitas['jumlah'])) ?>" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <?php foreach (['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'] as $v => $l): ?>
                                    <option value="<?= $v ?>" <?= old('kondisi', $fasilitas['kondisi']) === $v ? 'selected' : '' ?>><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"><?= esc(old('deskripsi', $fasilitas['deskripsi'])) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom"><i class="bi bi-image me-1 text-primary"></i>Foto & Urutan</div>
                <div class="card-body p-3">
                    <?php if (!empty($fasilitas['foto'])): ?>
                        <div class="mb-2 text-center" id="fotoWrap">
                            <img id="fotoPreview" src="<?= base_url('uploads/fasilitas/' . esc($fasilitas['foto'])) ?>"
                                class="rounded" style="max-height:120px;max-width:100%" alt="">
                        </div>
                    <?php else: ?>
                        <div class="mb-2 text-center d-none" id="fotoWrap"><img id="fotoPreview" src="" class="rounded" style="max-height:120px" alt=""></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto</label>
                        <input type="file" class="form-control" name="foto" id="fotoInput" accept="image/*">
                    </div>
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', $fasilitas['urutan'])) ?>" min="0">
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
document.getElementById('iconInput').addEventListener('input', function() { document.getElementById('iconPreview').className = 'bi ' + this.value; });
document.getElementById('fotoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) { const r = new FileReader(); r.onload = e => { document.getElementById('fotoPreview').src = e.target.result; document.getElementById('fotoWrap').classList.remove('d-none'); }; r.readAsDataURL(file); }
});
</script>
<?= $this->endSection() ?>
