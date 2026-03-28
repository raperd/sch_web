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
                    <input type="file" class="form-control" name="icon" id="iconFileInput" accept="image/*">
                    <div class="form-text mt-1">Gunakan gambar persegi. Gambar akan dipotong otomatis menjadi 256×256 px.</div>
                    <?php if (!empty($app['icon'])): ?>
                        <div class="mt-2">
                            <span class="d-block small text-muted mb-1">Ikon Saat Ini:</span>
                            <?php if (str_starts_with($app['icon'], 'bi-')): ?>
                                <i class="bi <?= esc($app['icon']) ?> fs-3 text-primary"></i>
                            <?php else: ?>
                                <img src="<?= base_url('uploads/aplikasi/' . esc($app['icon'])) ?>" alt="Icon" width="48" height="48" class="rounded object-fit-cover shadow-sm">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div id="iconPreviewWrap" class="mt-2 d-none">
                        <span class="d-block small text-muted mb-1">Preview Baru:</span>
                        <img id="iconPreviewImg" width="48" height="48" class="rounded object-fit-cover shadow-sm" alt="">
                    </div>
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

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.getElementById('iconFileInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    this.value = '';
    AppCrop.open(file, this, {
        bw: 256, bh: 256, ow: 256, oh: 256,
        fmt: 'image/png', quality: 1,
        onDone(blob, url) {
            document.getElementById('iconPreviewImg').src = url;
            document.getElementById('iconPreviewWrap').classList.remove('d-none');
        }
    });
});
</script>
<?= $this->endSection() ?>
