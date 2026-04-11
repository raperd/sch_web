<?= $this->extend('layouts/admin') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
.richtext-editor { min-height: 220px; font-size: 1rem; }
.ql-toolbar.ql-snow { border-radius: .375rem .375rem 0 0; background: #f8f9fa; }
.ql-container.ql-snow { border-radius: 0 0 .375rem .375rem; font-size: 0.95rem; }
</style>
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
                            <!-- Tombol terapkan langsung -->
                            <div class="mt-3 d-flex align-items-center gap-3">
                                <button type="button" id="btnSaveTema" class="btn btn-success">
                                    <i class="bi bi-save me-1"></i>Terapkan ke Website
                                </button>
                                <small class="text-muted">Langsung menerapkan tema ke halaman publik.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Label sebelum color picker custom -->
                <p class="fw-semibold text-muted small text-uppercase mb-2 mt-1" style="letter-spacing:.05em">
                    <i class="bi bi-palette me-1"></i>Atau pilih warna sendiri
                </p>
                <?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <?php foreach ($grouped[$grupKey] as $key => $setting): ?>
                                <div class="col-md-<?= in_array($setting['tipe'], ['textarea','richtext']) ? '12' : '6' ?>">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= esc($setting['label']) ?></label>

                                        <?php if ($setting['tipe'] === 'richtext'): ?>
                                            <div id="quill_<?= esc($key) ?>" class="richtext-editor"></div>
                                            <textarea name="pengaturan[<?= esc($key) ?>]"
                                                id="richtextInput_<?= esc($key) ?>"
                                                class="d-none"><?= esc($setting['setting_value']) ?></textarea>
                                            <div class="form-text">Mendukung format teks, daftar, dan tautan.</div>

                                        <?php elseif ($setting['tipe'] === 'textarea'): ?>
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
                                                    id="colorPicker_<?= esc($key) ?>"
                                                    name="pengaturan[<?= esc($key) ?>]"
                                                    value="<?= esc($setting['setting_value'] ?: '#1a5276') ?>"
                                                    style="width:56px;height:38px;">
                                                <input type="text" class="form-control font-monospace"
                                                    id="colorText_<?= esc($key) ?>"
                                                    value="<?= esc($setting['setting_value'] ?: '#1a5276') ?>"
                                                    maxlength="7" placeholder="#000000"
                                                    oninput="document.getElementById('colorPicker_<?= esc($key) ?>').value=this.value">
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
                                                class="form-control <?= in_array($key, ['foto_kepsek', 'hero_image_path', 'logo_path', 'favicon_path']) ? 'crop-input' : 'img-compress-input' ?>"
                                                name="pengaturan_file[<?= esc($key) ?>]"
                                                accept="<?= in_array($key, ['logo_path', 'favicon_path']) ? 'image/png,image/jpeg,image/webp' : 'image/jpeg,image/png,image/webp' ?>"
                                                data-aspect-ratio="<?= $key === 'foto_kepsek' || $key === 'favicon_path' ? '1' : ($key === 'hero_image_path' ? '1.7778' : '') ?>"
                                                data-crop-width="<?= $key === 'hero_image_path' ? '1200' : ($key === 'favicon_path' ? '256' : ($key === 'logo_path' ? '400' : '600')) ?>"
                                                data-crop-height="<?= $key === 'hero_image_path' ? '675' : ($key === 'favicon_path' ? '256' : ($key === 'logo_path' ? '400' : '600')) ?>"
                                                data-crop-quality="<?= $key === 'hero_image_path' ? '0.85' : '0.88' ?>"
                                                <?= in_array($key, ['logo_path', 'favicon_path']) ? 'data-preserve-alpha="true"' : '' ?>>
                                            <div class="form-text d-flex justify-content-between">
                                                <span>Upload baru untuk mengganti.<?= $key === 'hero_image_path' ? ' Dipotong rasio 16:9 (1200×675).' : ($key === 'logo_path' ? ' PNG transparan didukung.' : ($key === 'favicon_path' ? ' Output PNG 256×256.' : ' JPG/PNG/WebP.')) ?></span>
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
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
// Init Quill untuk setiap field richtext
(function() {
    const richtextEditors = document.querySelectorAll('[id^="quill_"]');
    richtextEditors.forEach(function(editorEl) {
        const key         = editorEl.id.replace('quill_', '');
        const hiddenInput = document.getElementById('richtextInput_' + key);
        if (!hiddenInput) return;

        const quill = new Quill(editorEl, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Load existing content
        const existing = hiddenInput.value;
        if (existing && existing.trim()) {
            quill.clipboard.dangerouslyPasteHTML(existing);
        }

        // Sync ke hidden input saat form submit
        document.getElementById('settingsForm').addEventListener('submit', function() {
            hiddenInput.value = quill.root.innerHTML;
        });
    });
})();
</script>

<!-- Crop Modal (untuk foto_kepsek, hero, logo, favicon) -->
<div class="modal fade" id="cropModalPengaturan" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalPengaturanTitle"><i class="bi bi-crop me-2"></i>Crop Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-dark p-3" style="min-height:300px">
                <img id="cropImgPengaturan" src="" style="max-width:100%;max-height:450px;">
            </div>
            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="pngZoomOut"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="pngZoomIn"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="pngRotL"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="pngRotR"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropConfirmPengaturan">
                        <i class="bi bi-check-lg me-1"></i>Crop &amp; Gunakan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cropperPengaturan = null;
let activeCropInputPengaturan = null;
let pengaturanCropApplied = false;

const cropTitles = {
    foto_kepsek:    'Crop Foto Kepala Sekolah (1:1)',
    hero_image_path:'Crop Hero Beranda (16:9 — 1200×675)',
    logo_path:      'Crop Logo Sekolah (bebas)',
    favicon_path:   'Crop Favicon (1:1 — 256×256)',
};

document.querySelectorAll('.crop-input').forEach(input => {
    input.addEventListener('change', function () {
        if (!this.files[0]) return;
        activeCropInputPengaturan = this;

        // Update modal title based on field name
        const fieldName = this.name.replace('pengaturan_file[', '').replace(']', '');
        const title = cropTitles[fieldName] || 'Crop Gambar';
        document.getElementById('cropModalPengaturanTitle').innerHTML = '<i class="bi bi-crop me-2"></i>' + title;

        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('cropImgPengaturan').src = e.target.result;
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cropModalPengaturan'));
            modal.show();
            document.getElementById('cropModalPengaturan').addEventListener('shown.bs.modal', () => {
                if (cropperPengaturan) cropperPengaturan.destroy();
                // Parse aspect ratio: empty string → NaN (free ratio), number → fixed
                const arStr = activeCropInputPengaturan.dataset.aspectRatio;
                const ar = arStr ? parseFloat(arStr) : NaN;
                cropperPengaturan = new Cropper(document.getElementById('cropImgPengaturan'), {
                    aspectRatio: ar,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.85,
                });
            }, { once: true });
        };
        reader.readAsDataURL(this.files[0]);
    });
});

document.getElementById('pngZoomIn').addEventListener('click',  () => cropperPengaturan?.zoom(0.1));
document.getElementById('pngZoomOut').addEventListener('click', () => cropperPengaturan?.zoom(-0.1));
document.getElementById('pngRotL').addEventListener('click',    () => cropperPengaturan?.rotate(-90));
document.getElementById('pngRotR').addEventListener('click',    () => cropperPengaturan?.rotate(90));

document.getElementById('cropConfirmPengaturan')?.addEventListener('click', function () {
    if (!cropperPengaturan || !activeCropInputPengaturan) return;

    const w           = parseInt(activeCropInputPengaturan.dataset.cropWidth)   || 600;
    const h           = parseInt(activeCropInputPengaturan.dataset.cropHeight)  || 600;
    const quality     = parseFloat(activeCropInputPengaturan.dataset.cropQuality) || 0.88;
    const preserveAlpha = activeCropInputPengaturan.dataset.preserveAlpha === 'true';
    const mimeType    = preserveAlpha ? 'image/png' : 'image/jpeg';
    const ext         = preserveAlpha ? 'png' : 'jpg';

    cropperPengaturan.getCroppedCanvas({ width: w, height: h, imageSmoothingQuality: 'high' }).toBlob(blob => {
        const file = new File([blob], 'foto-crop.' + ext, { type: mimeType });
        const dt = new DataTransfer(); dt.items.add(file);
        activeCropInputPengaturan.files = dt.files;
        // Update preview
        const wrap = activeCropInputPengaturan.closest('[class*="col-"]');
        const prev = wrap?.querySelector('.img-preview-current');
        if (prev) prev.src = URL.createObjectURL(blob);
        const newPrev = wrap?.querySelector('.img-preview-new');
        if (newPrev) { newPrev.querySelector('img').src = URL.createObjectURL(blob); newPrev.classList.remove('d-none'); }
        pengaturanCropApplied = true;
        bootstrap.Modal.getInstance(document.getElementById('cropModalPengaturan'))?.hide();
    }, mimeType, preserveAlpha ? undefined : quality);
});

document.getElementById('cropModalPengaturan').addEventListener('hidden.bs.modal', function () {
    if (cropperPengaturan) { cropperPengaturan.destroy(); cropperPengaturan = null; }
    if (!pengaturanCropApplied && activeCropInputPengaturan) {
        activeCropInputPengaturan.value = '';
    }
    pengaturanCropApplied = false;
});
</script>

<script>
/**
 * =====================================================================
 * COMPRESS + FORM SUBMIT
 * Semua gambar dikompres via Canvas sebelum dikirim ke server.
 * Menggunakan Map untuk menyimpan hasil kompres, lalu FormData + fetch
 * saat submit agar file yang dikirim PASTI yang sudah dikompres.
 * =====================================================================
 */
const compressedBlobs = new Map(); // input element → { file, fieldName }
let  pendingCompress  = 0;         // counter kompresi yg masih berjalan

function setSubmitState(busy) {
    const btn = document.querySelector('#settingsForm [type=submit]');
    if (!btn) return;
    btn.disabled = busy;
    btn.innerHTML = busy
        ? '<span class="spinner-border spinner-border-sm me-2"></span>Sedang mengompres...'
        : '<i class="bi bi-save me-1"></i>Simpan Semua Pengaturan';
}

document.querySelectorAll('.img-compress-input').forEach(input => {
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const wrap     = this.closest('[class*="col-"]');
        const preview  = wrap?.querySelector('.img-preview-new');
        const badge    = wrap?.querySelector('.img-compress-badge');
        const sizeInfo = wrap?.querySelector('.img-size-info');

        /* --- No-compress (logo, favicon) → simpan asli --- */
        if (this.dataset.noCompress === 'true') {
            if (badge)    badge.textContent = Math.round(file.size / 1024) + ' KB (asli)';
            if (preview)  { preview.querySelector('img').src = URL.createObjectURL(file); preview.classList.remove('d-none'); }
            compressedBlobs.set(this, { file, fieldName: this.name });
            return;
        }

        /* --- Compress via Canvas --- */
        pendingCompress++;
        setSubmitState(true);
        if (sizeInfo) sizeInfo.textContent = 'Sedang mengompres…';

        const maxW      = parseInt(this.dataset.maxWidth  || 1920);
        const maxH      = parseInt(this.dataset.maxHeight || 1080);
        const quality   = parseFloat(this.dataset.quality || 0.82);
        const forceJpeg = this.dataset.forceJpeg === 'true';
        const origType  = file.type;
        const outType   = forceJpeg ? 'image/jpeg'
                        : (origType === 'image/jpeg' || origType === 'image/jpg') ? 'image/jpeg'
                        : (origType === 'image/webp') ? 'image/webp' : 'image/png';
        const extMap    = {'image/jpeg':'.jpg','image/png':'.png','image/webp':'.webp'};
        const outExt    = extMap[outType] || '.jpg';
        const outQuality = outType === 'image/png' ? undefined : quality;

        const capturedInput = this;

        const reader = new FileReader();
        reader.onerror = () => { pendingCompress--; if (!pendingCompress) setSubmitState(false); };
        reader.onload  = ev => {
            const img = new Image();
            img.onerror = () => { pendingCompress--; if (!pendingCompress) setSubmitState(false); };
            img.onload  = () => {
                let w = img.width, h = img.height;
                if (w > maxW || h > maxH) {
                    const r = Math.min(maxW / w, maxH / h);
                    w = Math.round(w * r); h = Math.round(h * r);
                }
                const canvas = document.createElement('canvas');
                canvas.width = w; canvas.height = h;
                const ctx = canvas.getContext('2d');
                if (outType === 'image/jpeg') { ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, w, h); }
                ctx.drawImage(img, 0, 0, w, h);

                canvas.toBlob(blob => {
                    pendingCompress--;
                    if (!pendingCompress) setSubmitState(false);
                    if (!blob) return;

                    const compressed = new File([blob],
                        file.name.replace(/\.[^.]+$/, outExt), { type: outType });

                    // Simpan ke Map (digunakan saat form submit)
                    compressedBlobs.set(capturedInput, { file: compressed, fieldName: capturedInput.name });

                    // Update input.files (opsional, fallback)
                    try { const dt = new DataTransfer(); dt.items.add(compressed); capturedInput.files = dt.files; } catch(_) {}

                    // Tampilkan info
                    const kb     = Math.round(compressed.size / 1024);
                    const origKb = Math.round(file.size / 1024);
                    const saved  = Math.round((1 - compressed.size / file.size) * 100);
                    if (badge)    badge.textContent    = `${kb} KB (hemat ${saved}% dari ${origKb} KB)`;
                    if (sizeInfo) sizeInfo.textContent = `${w}×${h}px`;
                    if (preview)  { preview.querySelector('img').src = URL.createObjectURL(blob); preview.classList.remove('d-none'); }
                }, outType, outQuality);
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
});

/* Form submit — gunakan FormData + fetch agar file kompres PASTI terkirim */
document.getElementById('settingsForm')?.addEventListener('submit', function (e) {
    if (compressedBlobs.size === 0) return; // tidak ada gambar → submit biasa
    e.preventDefault();

    if (pendingCompress > 0) {
        alert('Tunggu sebentar, gambar sedang dikompres…');
        return;
    }

    setSubmitState(true);
    const fd = new FormData(this);

    // Ganti field file dengan blob yang sudah dikompres
    compressedBlobs.forEach(({ file, fieldName }) => {
        if (file && fieldName) fd.set(fieldName, file, file.name);
    });

    fetch(this.action, { method: 'POST', body: fd })
        .then(r => { window.location.href = r.url || window.location.href; })
        .catch(() => { setSubmitState(false); alert('Gagal menyimpan. Coba lagi.'); });
});

/* =====================================================================
 * THEME SWITCHER + COLOR PICKERS
 * Simpan warna via AJAX saat preset dipilih agar langsung terlihat di publik
 * ===================================================================== */

/* Sync color picker → text input */
document.querySelectorAll('input[type=color]').forEach(picker => {
    picker.addEventListener('input', function () {
        const key = this.id.replace('colorPicker_', '');
        const txt = document.getElementById('colorText_' + key);
        if (txt) txt.value = this.value;
    });
});

function applyColorPreset(primary, secondary, accent) {
    const map = { tema_primary: primary, tema_secondary: secondary, tema_accent: accent };
    Object.entries(map).forEach(([key, val]) => {
        const p = document.getElementById('colorPicker_' + key);
        const t = document.getElementById('colorText_'   + key);
        if (p) p.value = val;
        if (t) t.value = val;
    });
}

/* Simpan warna tema via AJAX (tanpa reload halaman) */
function saveThemeAjax(primary, secondary, accent) {
    const btn = document.getElementById('btnSaveTema');
    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }

    const fd = new FormData();
    fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    fd.append('pengaturan[tema_primary]',   primary);
    fd.append('pengaturan[tema_secondary]', secondary);
    fd.append('pengaturan[tema_accent]',    accent);

    fetch('<?= base_url('admin/pengaturan/update') ?>', { method: 'POST', body: fd })
        .then(() => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Tersimpan!'; }
            setTimeout(() => { if (btn) btn.innerHTML = '<i class="bi bi-save me-1"></i>Terapkan ke Website'; }, 2000);
        })
        .catch(() => { if (btn) { btn.disabled = false; btn.innerHTML = 'Coba lagi'; } });
}

document.querySelectorAll('.preset-card').forEach(card => {
    card.addEventListener('click', function () {
        const { primary, secondary, accent } = this.dataset;
        applyColorPreset(primary, secondary, accent);
        document.querySelectorAll('.preset-card').forEach(c => c.classList.remove('border-primary','border-2','shadow'));
        this.classList.add('border-primary', 'border-2', 'shadow');
        saveThemeAjax(primary, secondary, accent);
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
