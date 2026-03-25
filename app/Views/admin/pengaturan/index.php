<?= $this->extend('layouts/admin') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="fw-bold mb-0">Pengaturan Situs</h4>
    <p class="text-muted small mb-0">Kelola informasi dan tampilan website sekolah</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/pengaturan/update') ?>" enctype="multipart/form-data" id="settingsForm">
    <?= csrf_field() ?>

    <!-- Tabs -->
    <?php
    $tabLabels = [
        'umum'       => ['Umum', 'bi-gear'],
        'hero'       => ['Hero / Beranda', 'bi-image'],
        'profil'     => ['Profil Sekolah', 'bi-building'],
        'statistik'  => ['Statistik', 'bi-bar-chart'],
        'sosial'     => ['Media Sosial', 'bi-share'],
        'ppdb'       => ['SPMB', 'bi-clipboard2-check'],
        'tema'       => ['Tema Warna', 'bi-palette'],
    ];
    $firstTab = true;
    ?>
    <ul class="nav nav-tabs mb-4" id="settingTabs">
        <?php foreach ($tabLabels as $key => [$label, $icon]): ?>
            <?php if (isset($grouped[$key])): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $firstTab ? 'active' : '' ?>"
                       data-bs-toggle="tab" href="#tab-<?= $key ?>">
                        <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
                    </a>
                </li>
                <?php $firstTab = false; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content">
        <?php $firstTab = true; ?>
        <?php foreach ($tabLabels as $grupKey => [$label, $icon]): ?>
            <?php if (!isset($grouped[$grupKey])) continue; ?>
            <div class="tab-pane fade <?= $firstTab ? 'show active' : '' ?>" id="tab-<?= $grupKey ?>">

                <?php if ($grupKey === 'tema'): ?>
                <!-- Preset Tema -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Preset Tema</label>
                            <div class="row g-2" id="themePresets">
                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="card border preset-card h-100" style="cursor:pointer"
                                        data-primary="#1a5276" data-secondary="#2e86c1" data-accent="#d4ac0d">
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex justify-content-center gap-1 mb-2">
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#1a5276"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#2e86c1"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#d4ac0d"></span>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:.7rem">Biru Navy</small>
                                            <small class="text-muted" style="font-size:.65rem">Default</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="card border preset-card h-100" style="cursor:pointer"
                                        data-primary="#0d6efd" data-secondary="#0dcaf0" data-accent="#ffc107">
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex justify-content-center gap-1 mb-2">
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#0d6efd"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#0dcaf0"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#ffc107"></span>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:.7rem">Biru Langit</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="card border preset-card h-100" style="cursor:pointer"
                                        data-primary="#145a32" data-secondary="#1e8449" data-accent="#f39c12">
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex justify-content-center gap-1 mb-2">
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#145a32"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#1e8449"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#f39c12"></span>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:.7rem">Hijau Alam</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="card border preset-card h-100" style="cursor:pointer"
                                        data-primary="#922b21" data-secondary="#e74c3c" data-accent="#2471a3">
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex justify-content-center gap-1 mb-2">
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#922b21"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#e74c3c"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#2471a3"></span>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:.7rem">Merah Putih</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="card border preset-card h-100" style="cursor:pointer"
                                        data-primary="#4a235a" data-secondary="#8e44ad" data-accent="#d4ac0d">
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex justify-content-center gap-1 mb-2">
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#4a235a"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#8e44ad"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#d4ac0d"></span>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:.7rem">Ungu Elegan</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="card border preset-card h-100" style="cursor:pointer"
                                        data-primary="#212529" data-secondary="#495057" data-accent="#0d6efd">
                                        <div class="card-body p-2 text-center">
                                            <div class="d-flex justify-content-center gap-1 mb-2">
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#212529"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#495057"></span>
                                                <span class="rounded-circle d-inline-block" style="width:18px;height:18px;background:#0d6efd"></span>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:.7rem">Abu Profesional</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <?php foreach ($grouped[$grupKey] as $key => $setting): ?>
                                <div class="col-md-<?= in_array($setting['tipe'], ['textarea']) ? '12' : '6' ?>">
                                    <label class="form-label fw-semibold"><?= esc($setting['label']) ?></label>

                                    <?php if ($setting['tipe'] === 'textarea'): ?>
                                        <textarea class="form-control" name="pengaturan[<?= esc($key) ?>]" rows="4"><?= esc($setting['setting_value']) ?></textarea>

                                    <?php elseif ($setting['tipe'] === 'boolean'): ?>
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input" type="checkbox"
                                                name="pengaturan[<?= esc($key) ?>]" value="1"
                                                <?= $setting['setting_value'] == '1' ? 'checked' : '' ?>>
                                            <label class="form-check-label">Aktifkan</label>
                                        </div>

                                    <?php elseif ($setting['tipe'] === 'color'): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" class="form-control form-control-color"
                                                name="pengaturan[<?= esc($key) ?>]"
                                                value="<?= esc($setting['setting_value'] ?: '#1a5276') ?>"
                                                style="width:56px;height:38px;">
                                            <input type="text" class="form-control font-monospace"
                                                id="colorText_<?= esc($key) ?>"
                                                value="<?= esc($setting['setting_value'] ?: '#1a5276') ?>"
                                                maxlength="7" placeholder="#000000"
                                                oninput="document.querySelector('[name=\'pengaturan[<?= esc($key) ?>]\'][type=color]').value=this.value">
                                        </div>
                                        <div class="form-text">Format: #RRGGBB</div>

                                    <?php elseif ($setting['tipe'] === 'image'): ?>
                                        <?php if (!empty($setting['setting_value'])): ?>
                                            <div class="mb-2">
                                                <img src="<?= base_url('uploads/pengaturan/' . esc($setting['setting_value'])) ?>"
                                                    class="img-preview-current"
                                                    style="max-height:80px;border-radius:.375rem" alt="Current">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file"
                                            class="form-control <?= in_array($key, ['foto_kepsek']) ? 'crop-input' : 'img-compress-input' ?>"
                                            name="pengaturan_file[<?= esc($key) ?>]"
                                            accept="<?= $key === 'favicon_path' ? '.ico,.png,.svg,image/x-icon,image/png,image/svg+xml' : 'image/jpeg,image/png,image/webp' ?>"
                                            data-max-width="<?= $key === 'hero_image_path' ? '1920' : '800' ?>"
                                            data-max-height="<?= $key === 'hero_image_path' ? '1080' : '800' ?>"
                                            data-quality="0.85"
                                            data-aspect-ratio="<?= $key === 'foto_kepsek' ? '1' : '' ?>"
                                            <?= in_array($key, ['logo_path', 'favicon_path']) ? 'data-no-compress="true"' : '' ?>
                                            <?= $key === 'logo_path' ? 'data-preserve-alpha="true"' : '' ?>>
                                        <div class="form-text d-flex justify-content-between">
                                            <span>Upload baru untuk mengganti.<?= $key === 'favicon_path' ? ' ICO/PNG/SVG.' : ' JPG/PNG/WebP.' ?></span>
                                            <span class="img-size-info text-muted"></span>
                                        </div>
                                        <div class="img-preview-new mt-2 d-none">
                                            <img src="" class="rounded" style="max-height:120px;" alt="Preview">
                                            <span class="badge text-bg-success ms-2 img-compress-badge"></span>
                                        </div>

                                    <?php else: ?>
                                        <input type="text" class="form-control"
                                            name="pengaturan[<?= esc($key) ?>]"
                                            value="<?= esc($setting['setting_value']) ?>">
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $firstTab = false; ?>
        <?php endforeach; ?>
    </div>

    <div class="d-flex gap-2 justify-content-end mt-2 pb-4">
        <button type="submit" class="btn btn-primary btn-lg fw-semibold px-5">
            <i class="bi bi-save me-1"></i>Simpan Semua Pengaturan
        </button>
    </div>
</form>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<!-- Crop Modal (untuk foto_kepsek) -->
<div class="modal fade" id="cropModalPengaturan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop me-2"></i>Crop Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-dark p-3" style="min-height:300px">
                <img id="cropImgPengaturan" src="" style="max-width:100%;max-height:450px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="cropConfirmPengaturan">
                    <i class="bi bi-check-lg me-1"></i>Crop & Gunakan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cropperPengaturan = null;
let activeCropInputPengaturan = null;

document.querySelectorAll('.crop-input').forEach(input => {
    input.addEventListener('change', function () {
        if (!this.files[0]) return;
        activeCropInputPengaturan = this;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('cropImgPengaturan').src = e.target.result;
            const modal = new bootstrap.Modal(document.getElementById('cropModalPengaturan'));
            modal.show();
            document.getElementById('cropModalPengaturan').addEventListener('shown.bs.modal', () => {
                if (cropperPengaturan) cropperPengaturan.destroy();
                cropperPengaturan = new Cropper(document.getElementById('cropImgPengaturan'), {
                    aspectRatio: parseFloat(activeCropInputPengaturan.dataset.aspectRatio || 1),
                    viewMode: 1, autoCropArea: 0.85,
                });
            }, { once: true });
        };
        reader.readAsDataURL(this.files[0]);
    });
});

document.getElementById('cropConfirmPengaturan')?.addEventListener('click', function () {
    if (!cropperPengaturan || !activeCropInputPengaturan) return;
    cropperPengaturan.getCroppedCanvas({ width: 600, height: 600 }).toBlob(blob => {
        const file = new File([blob], 'foto-crop.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer(); dt.items.add(file);
        activeCropInputPengaturan.files = dt.files;
        // Update preview
        const wrap = activeCropInputPengaturan.closest('.col-md-6');
        const prev = wrap?.querySelector('.img-preview-current');
        if (prev) prev.src = URL.createObjectURL(blob);
        bootstrap.Modal.getInstance(document.getElementById('cropModalPengaturan'))?.hide();
    }, 'image/jpeg', 0.88);
});
</script>

<script>
/* Client-side image compress & resize */
document.querySelectorAll('.img-compress-input').forEach(input => {
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const wrap      = this.closest('.col-md-6, .col-md-12');
        const preview   = wrap.querySelector('.img-preview-new');
        const previewImg = preview.querySelector('img');
        const badge     = preview.querySelector('.img-compress-badge');
        const sizeInfo  = wrap.querySelector('.img-size-info');

        // Skip compress for logo, foto kepsek, and favicon — preserve original file
        if (this.dataset.noCompress === 'true') {
            const kb = Math.round(file.size / 1024);
            badge.textContent = `${kb} KB`;
            previewImg.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            return;
        }

        const maxW    = parseInt(this.dataset.maxWidth  || 1920);
        const maxH    = parseInt(this.dataset.maxHeight || 1080);
        const quality  = parseFloat(this.dataset.quality || 0.82);

        // Determine output format: preserve PNG and WebP, only convert JPEG to JPEG
        const originalType = file.type;
        const outType = (originalType === 'image/jpeg' || originalType === 'image/jpg')
            ? 'image/jpeg'
            : (originalType === 'image/webp' ? 'image/webp' : 'image/png');
        const extMap  = { 'image/jpeg': '.jpg', 'image/png': '.png', 'image/webp': '.webp' };
        const outExt  = extMap[outType] || '.jpg';
        const outQuality = outType === 'image/png' ? undefined : quality;

        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => {
                let w = img.width, h = img.height;
                if (w > maxW || h > maxH) {
                    const ratio = Math.min(maxW / w, maxH / h);
                    w = Math.round(w * ratio);
                    h = Math.round(h * ratio);
                }
                const canvas = document.createElement('canvas');
                canvas.width = w; canvas.height = h;
                const ctx = canvas.getContext('2d');
                // For JPEG only: fill white background to flatten transparency
                if (outType === 'image/jpeg') {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, w, h);
                }
                ctx.drawImage(img, 0, 0, w, h);
                canvas.toBlob(blob => {
                    if (!blob) return;
                    const compressed = new File([blob], file.name.replace(/\.[^.]+$/, outExt), { type: outType });
                    const dt = new DataTransfer();
                    dt.items.add(compressed);
                    input.files = dt.files;
                    const kb     = Math.round(compressed.size / 1024);
                    const origKb = Math.round(file.size / 1024);
                    badge.textContent   = `${kb} KB (dari ${origKb} KB)`;
                    sizeInfo.textContent = `${w}×${h}px`;
                    previewImg.src = URL.createObjectURL(blob);
                    preview.classList.remove('d-none');
                }, outType, outQuality);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});

/* Sync color text input → color picker */
document.querySelectorAll('input[type=color]').forEach(picker => {
    picker.addEventListener('input', function () {
        const key = this.name.replace(/^pengaturan\[(.+)\]$/, '$1');
        const textInput = document.getElementById('colorText_' + key);
        if (textInput) textInput.value = this.value;
    });
});

/* Theme presets */
document.querySelectorAll('.preset-card').forEach(card => {
    card.addEventListener('click', function () {
        const primary   = this.dataset.primary;
        const secondary = this.dataset.secondary;
        const accent    = this.dataset.accent;

        // Update color pickers
        const pPicker = document.querySelector('[name="pengaturan[tema_primary]"][type=color]');
        const sPicker = document.querySelector('[name="pengaturan[tema_secondary]"][type=color]');
        const aPicker = document.querySelector('[name="pengaturan[tema_accent]"][type=color]');
        if (pPicker) pPicker.value = primary;
        if (sPicker) sPicker.value = secondary;
        if (aPicker) aPicker.value = accent;

        // Update text inputs
        const pText = document.getElementById('colorText_tema_primary');
        const sText = document.getElementById('colorText_tema_secondary');
        const aText = document.getElementById('colorText_tema_accent');
        if (pText) pText.value = primary;
        if (sText) sText.value = secondary;
        if (aText) aText.value = accent;

        // Visual feedback: highlight selected preset
        document.querySelectorAll('.preset-card').forEach(c => c.classList.remove('border-primary', 'border-2'));
        this.classList.add('border-primary', 'border-2');
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
