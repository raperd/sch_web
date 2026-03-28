<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/kategori-artikel') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><?= $kategori ? 'Edit Kategori' : 'Tambah Kategori' ?></h4>
        <small class="text-muted">Kategori digunakan untuk mengelompokkan berita & artikel</small>
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

<?php
$action = $kategori
    ? base_url('admin/kategori-artikel/update/' . $kategori['id'])
    : base_url('admin/kategori-artikel/store');
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="<?= $action ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" id="namaInput"
                            value="<?= esc(old('nama', $kategori['nama'] ?? '')) ?>"
                            placeholder="Contoh: Pengumuman, Prestasi, OSIS..."
                            required maxlength="100"
                            oninput="autoSlug()">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted">/berita?kategori=</span>
                            <input type="text" name="slug" class="form-control font-monospace" id="slugInput"
                                value="<?= esc(old('slug', $kategori['slug'] ?? '')) ?>"
                                placeholder="otomatis-dari-nama" maxlength="120">
                        </div>
                        <div class="form-text">Kosongkan untuk generate otomatis dari nama.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                            placeholder="Deskripsi singkat kategori ini (opsional)..."><?= esc(old('deskripsi', $kategori['deskripsi'] ?? '')) ?></textarea>
                    </div>

                    <div class="mb-4" style="max-width:160px">
                        <label class="form-label fw-semibold">Urutan Tampil</label>
                        <input type="number" name="urutan" class="form-control" min="0"
                            value="<?= esc(old('urutan', $kategori['urutan'] ?? 0)) ?>">
                        <div class="form-text">Angka kecil tampil lebih awal.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i><?= $kategori ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
                        </button>
                        <a href="<?= base_url('admin/kategori-artikel') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toSlug(str) {
    return str.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim().replace(/\s+/g, '-');
}
function autoSlug() {
    const slug = document.getElementById('slugInput');
    // Hanya auto-fill jika slug belum diubah manual
    if (slug.dataset.manual !== '1') {
        slug.value = toSlug(document.getElementById('namaInput').value);
    }
}
document.getElementById('slugInput').addEventListener('input', function() {
    this.dataset.manual = this.value ? '1' : '0';
});
</script>
<?= $this->endSection() ?>
