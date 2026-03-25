<?= $this->extend('layouts/admin') ?>
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
        'umum'   => ['Umum', 'bi-gear'],
        'hero'   => ['Hero / Beranda', 'bi-image'],
        'profil' => ['Profil Sekolah', 'bi-building'],
        'sosial' => ['Media Sosial', 'bi-share'],
        'ppdb'   => ['SPMB', 'bi-clipboard2-check'],
        'tema'   => ['Tema Warna', 'bi-palette'],
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
                                            class="form-control img-compress-input"
                                            name="pengaturan_file[<?= esc($key) ?>]"
                                            accept="image/jpeg,image/png,image/webp"
                                            data-max-width="1920"
                                            data-max-height="1080"
                                            data-quality="0.82">
                                        <div class="form-text d-flex justify-content-between">
                                            <span>Upload baru untuk mengganti. JPG/PNG/WebP.</span>
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
<script>
/* Client-side image compress & resize */
document.querySelectorAll('.img-compress-input').forEach(input => {
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const maxW   = parseInt(this.dataset.maxWidth  || 1920);
        const maxH   = parseInt(this.dataset.maxHeight || 1080);
        const quality = parseFloat(this.dataset.quality || 0.82);
        const wrap   = this.closest('.col-md-6, .col-md-12');
        const preview = wrap.querySelector('.img-preview-new');
        const previewImg = preview.querySelector('img');
        const badge  = preview.querySelector('.img-compress-badge');
        const sizeInfo = wrap.querySelector('.img-size-info');

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
                canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                canvas.toBlob(blob => {
                    if (!blob) return;
                    const compressed = new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(compressed);
                    input.files = dt.files;
                    const kb = Math.round(compressed.size / 1024);
                    const origKb = Math.round(file.size / 1024);
                    badge.textContent = `${kb} KB (dari ${origKb} KB)`;
                    sizeInfo.textContent = `${w}×${h}px`;
                    previewImg.src = URL.createObjectURL(blob);
                    preview.classList.remove('d-none');
                }, 'image/jpeg', quality);
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
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
