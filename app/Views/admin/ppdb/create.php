<?= $this->extend('layouts/admin') ?>
<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>#quill-editor{min-height:280px;font-size:1rem}.ql-toolbar.ql-snow{border-radius:.375rem .375rem 0 0}.ql-container.ql-snow{border-radius:0 0 .375rem .375rem}</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/ppdb') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Tambah Konten PPDB</h4>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/ppdb/store') ?>" id="ppdbForm">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Blok <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul_blok" value="<?= esc(old('judul_blok')) ?>" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                        <div id="quill-editor"><?= old('konten') ?></div>
                        <textarea name="konten" id="kontenInput" class="d-none"><?= old('konten') ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom"><i class="bi bi-tag me-1 text-primary"></i>Pengaturan</div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select" required>
                            <?php foreach (['persyaratan' => 'Persyaratan', 'jadwal' => 'Jadwal', 'alur' => 'Alur Pendaftaran', 'faq' => 'FAQ', 'info' => 'Info'] as $v => $l): ?>
                                <option value="<?= $v ?>" <?= old('tipe') === $v ? 'selected' : '' ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="<?= esc(old('urutan', 0)) ?>" min="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                        <label class="form-check-label" for="isActive">Aktif / Tampilkan</label>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold"><i class="bi bi-save me-1"></i>Simpan</button>
                    <a href="<?= base_url('admin/ppdb') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const quill = new Quill('#quill-editor', { theme: 'snow', modules: { toolbar: [[{header:[2,3,false]}],['bold','italic','underline'],[{list:'ordered'},{list:'bullet'}],['link'],['clean']] } });
document.getElementById('ppdbForm').addEventListener('submit', () => { document.getElementById('kontenInput').value = quill.root.innerHTML; });
</script>
<?= $this->endSection() ?>
