<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= admin_url('quick-links') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><?= esc($title) ?></h4>
        <small class="text-muted">Quick link akan tampil sebagai tombol pintasan di beranda</small>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="post"
                    action="<?= $link
                        ? admin_url('quick-links/update/' . $link['id'])
                        : admin_url('quick-links/store') ?>">
                    <?= csrf_field() ?>

                    <!-- Label -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control"
                            value="<?= esc(old('label', $link['label'] ?? '')) ?>"
                            placeholder="Contoh: SPMB 2026/2027" required maxlength="100">
                    </div>

                    <!-- URL -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL / Tautan <span class="text-danger">*</span></label>
                        <input type="text" name="url" class="form-control"
                            value="<?= esc(old('url', $link['url'] ?? '')) ?>"
                            placeholder="Contoh: /ppdb atau https://..." required maxlength="255">
                        <div class="form-text">Gunakan path relatif (misal <code>/ppdb</code>) atau URL lengkap.</div>
                    </div>

                    <!-- Ikon -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ikon Bootstrap Icons <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="iconPreview">
                                <i class="bi <?= esc(old('icon', $link['icon'] ?? 'bi-link-45deg')) ?>" id="iconPreviewEl"></i>
                            </span>
                            <input type="text" name="icon" id="iconInput" class="form-control font-monospace"
                                value="<?= esc(old('icon', $link['icon'] ?? 'bi-link-45deg')) ?>"
                                placeholder="bi-link-45deg" required maxlength="100">
                        </div>
                        <div class="form-text">
                            Cari ikon di <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">icons.getbootstrap.com</a>.
                            Contoh: <code>bi-clipboard-check</code>, <code>bi-book-half</code>, <code>bi-trophy</code>
                        </div>
                    </div>

                    <!-- Warna -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Warna Tombol</label>
                        <?php
                        $warnaOptions = [
                            'primary'   => 'Biru (Primary)',
                            'secondary' => 'Abu-abu (Secondary)',
                            'success'   => 'Hijau (Success)',
                            'danger'    => 'Merah (Danger)',
                            'warning'   => 'Kuning (Warning)',
                            'info'      => 'Cyan (Info)',
                            'dark'      => 'Hitam (Dark)',
                            'light'     => 'Terang (Light)',
                        ];
                        $selectedWarna = old('warna', $link['warna'] ?? 'primary');
                        ?>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <?php foreach ($warnaOptions as $val => $label): ?>
                                <label class="d-flex align-items-center gap-1 cursor-pointer">
                                    <input type="radio" name="warna" value="<?= $val ?>"
                                        class="btn-check" id="warna_<?= $val ?>"
                                        <?= $selectedWarna === $val ? 'checked' : '' ?>>
                                    <span class="btn btn-<?= $val ?> btn-sm <?= $val === 'light' ? 'border' : '' ?>"
                                        style="min-width:90px;" onclick="document.getElementById('warna_<?= $val ?>').checked=true">
                                        <?= $label ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Target & Urutan -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Buka Di</label>
                            <select name="target" class="form-select">
                                <option value="_self"  <?= (old('target', $link['target'] ?? '_self')) === '_self'  ? 'selected' : '' ?>>Halaman Ini</option>
                                <option value="_blank" <?= (old('target', $link['target'] ?? '_self')) === '_blank' ? 'selected' : '' ?>>Tab Baru</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="urutan" class="form-control" min="0"
                                value="<?= esc(old('urutan', $link['urutan'] ?? ($next_urutan ?? 0))) ?>">
                            <div class="form-text">Angka kecil tampil lebih dulu.</div>
                        </div>
                    </div>

                    <!-- Aktif -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                <?= old('is_active', $link['is_active'] ?? 1) == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="isActive">Tampilkan di beranda</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i><?= $link ? 'Simpan Perubahan' : 'Tambah Link' ?>
                        </button>
                        <a href="<?= admin_url('quick-links') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview card -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 fw-semibold">
                <i class="bi bi-eye me-2 text-primary"></i>Preview Tombol
            </div>
            <div class="card-body text-center py-4">
                <a href="#" class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2 shadow-sm" id="btnPreview" style="pointer-events:none;">
                    <i class="bi bi-link-45deg" id="previewIcon"></i>
                    <span id="previewLabel">Label</span>
                </a>
                <p class="text-muted small mt-3 mb-0">Tampilan perkiraan di beranda</p>
            </div>
        </div>
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body p-3">
                <p class="small fw-semibold mb-2"><i class="bi bi-info-circle text-primary me-1"></i>Ikon yang tersedia (contoh):</p>
                <div class="d-flex flex-wrap gap-2">
                    <?php
                    $contohIkon = [
                        'bi-clipboard-check','bi-book-half','bi-trophy','bi-person-badge',
                        'bi-images','bi-newspaper','bi-calendar-event','bi-mortarboard',
                        'bi-award','bi-house','bi-telephone','bi-envelope',
                    ];
                    foreach ($contohIkon as $ic): ?>
                        <button type="button" class="btn btn-sm btn-outline-secondary icon-pick-btn"
                            title="<?= $ic ?>" data-icon="<?= $ic ?>">
                            <i class="bi <?= $ic ?>"></i>
                        </button>
                    <?php endforeach; ?>
                </div>
                <p class="text-muted small mt-2 mb-0">Klik ikon di atas untuk langsung menggunakannya.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Warna Bootstrap → class CSS (untuk preview)
const warnaMap = {
    primary:'#0d6efd', secondary:'#6c757d', success:'#198754',
    danger:'#dc3545', warning:'#ffc107', info:'#0dcaf0', dark:'#212529', light:'#f8f9fa'
};
const warnaText = { warning:'#000', light:'#000' };

function updatePreview() {
    const label  = document.querySelector('[name="label"]').value || 'Label';
    const icon   = document.getElementById('iconInput').value || 'bi-link-45deg';
    const warna  = document.querySelector('[name="warna"]:checked')?.value || 'primary';

    document.getElementById('previewLabel').textContent = label;
    document.getElementById('previewIcon').className = 'bi ' + icon;
    document.getElementById('iconPreviewEl').className = 'bi ' + icon;

    const btn = document.getElementById('btnPreview');
    btn.className = 'btn btn-' + warna + ' btn-lg d-inline-flex align-items-center gap-2 shadow-sm';
    btn.style.pointerEvents = 'none';
}

document.querySelector('[name="label"]').addEventListener('input', updatePreview);
document.getElementById('iconInput').addEventListener('input', updatePreview);
document.querySelectorAll('[name="warna"]').forEach(r => r.addEventListener('change', updatePreview));

// Klik ikon contoh → isi input
document.querySelectorAll('.icon-pick-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('iconInput').value = this.dataset.icon;
        updatePreview();
    });
});

updatePreview();
</script>
<?= $this->endSection() ?>
