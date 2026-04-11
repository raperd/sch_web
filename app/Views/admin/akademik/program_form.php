<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('admin/akademik/program') ?>" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Program Unggulan
    </a>
    <h4 class="fw-bold mt-1 mb-0"><?= esc($title) ?></h4>
</div>

<?php if (session()->getFlashdata('errors') || isset($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            <?php foreach ((session()->getFlashdata('errors') ?? $errors ?? []) as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php
$isEdit  = !empty($program);
$action  = $isEdit
    ? base_url('admin/akademik/program/' . $program['id'] . '/update')
    : base_url('admin/akademik/program/store');
$val     = fn(string $k, $default = '') => old($k, $isEdit ? ($program[$k] ?? $default) : $default);
$colors  = ['primary','secondary','success','danger','warning','info','dark'];
$icons   = [
    'bi-laptop','bi-globe2','bi-trophy','bi-heart-pulse','bi-easel','bi-people',
    'bi-book','bi-lightbulb','bi-gear','bi-star','bi-shield-check','bi-cpu',
    'bi-music-note-beamed','bi-camera','bi-palette','bi-rocket-takeoff','bi-graph-up',
];
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="post" action="<?= $action ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Program <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul"
                               value="<?= esc($val('judul')) ?>" required maxlength="150">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Singkat</label>
                        <textarea class="form-control" name="deskripsi" rows="3" maxlength="300"><?= esc($val('deskripsi')) ?></textarea>
                        <div class="form-text">Tampil di bawah judul kartu program. Maks 300 karakter.</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Icon Bootstrap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="iconPreview">
                                    <i class="bi <?= esc($val('icon', 'bi-star')) ?>" id="iconPreviewI"></i>
                                </span>
                                <input type="text" class="form-control font-monospace" name="icon"
                                       id="iconInput" value="<?= esc($val('icon', 'bi-star')) ?>"
                                       required maxlength="60"
                                       placeholder="bi-star">
                            </div>
                            <div class="form-text">
                                <a href="https://icons.getbootstrap.com/" target="_blank">Cari icon Bootstrap Icons <i class="bi bi-box-arrow-up-right"></i></a>
                            </div>
                            <!-- Quick pick icons -->
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                <?php foreach ($icons as $ic): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary icon-pick" data-icon="<?= $ic ?>" title="<?= $ic ?>">
                                        <i class="bi <?= $ic ?>"></i>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                <?php foreach ($colors as $c): ?>
                                    <label class="d-flex align-items-center gap-1 cursor-pointer">
                                        <input type="radio" name="warna" value="<?= $c ?>"
                                               <?= $val('warna', 'primary') === $c ? 'checked' : '' ?>>
                                        <span class="badge text-bg-<?= $c ?> px-3"><?= ucfirst($c) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" class="form-control" name="urutan"
                                   value="<?= esc($val('urutan', $next_urutan ?? 0)) ?>" min="0" max="99">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       value="1" id="isActive"
                                       <?= $val('is_active', 1) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="isActive">Tampilkan di halaman publik</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-save me-1"></i><?= $isEdit ? 'Perbarui' : 'Simpan' ?>
                        </button>
                        <a href="<?= base_url('admin/akademik/program') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-transparent fw-semibold">Preview Kartu</div>
            <div class="card-body text-center p-4" id="cardPreview">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     id="previewCircle"
                     style="width:72px;height:72px;background:rgba(var(--bs-primary-rgb),.1)">
                    <i class="bi <?= esc($val('icon', 'bi-star')) ?> fs-2" id="previewIcon" style="color:var(--bs-primary)"></i>
                </div>
                <h6 class="fw-bold" id="previewTitle"><?= esc($val('judul', 'Judul Program')) ?></h6>
                <p class="text-muted small mb-0" id="previewDesc"><?= esc($val('deskripsi', 'Deskripsi singkat program ini...')) ?></p>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Icon pick buttons
document.querySelectorAll('.icon-pick').forEach(btn => {
    btn.addEventListener('click', () => {
        const ic = btn.dataset.icon;
        document.getElementById('iconInput').value = ic;
        document.getElementById('iconPreviewI').className = 'bi ' + ic;
        document.getElementById('previewIcon').className = 'bi ' + ic + ' fs-2';
    });
});

// Live icon input
document.getElementById('iconInput').addEventListener('input', function () {
    document.getElementById('iconPreviewI').className = 'bi ' + this.value;
    document.getElementById('previewIcon').className = 'bi ' + this.value + ' fs-2';
});

// Live judul
document.querySelector('[name=judul]').addEventListener('input', function () {
    document.getElementById('previewTitle').textContent = this.value || 'Judul Program';
});

// Live deskripsi
document.querySelector('[name=deskripsi]').addEventListener('input', function () {
    document.getElementById('previewDesc').textContent = this.value || 'Deskripsi singkat program ini...';
});

// Live warna
document.querySelectorAll('[name=warna]').forEach(r => {
    r.addEventListener('change', () => {
        const circle = document.getElementById('previewCircle');
        const icon   = document.getElementById('previewIcon');
        // update inline color via CSS var override trick — Bootstrap classes needed
        circle.style.background = '';
        icon.style.color = '';
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
