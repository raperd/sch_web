<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="fw-bold mb-0">Pengaturan Situs</h4>
    <p class="text-muted small mb-0">Kelola informasi dan tampilan website sekolah</p>
</div>

<form method="post" action="<?= base_url('admin/pengaturan/update') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Tabs -->
    <?php
    $tabLabels = [
        'umum'   => ['Umum', 'bi-gear'],
        'hero'   => ['Hero / Beranda', 'bi-image'],
        'profil' => ['Profil Sekolah', 'bi-building'],
        'sosial' => ['Media Sosial', 'bi-share'],
        'ppdb'   => ['PPDB', 'bi-clipboard2-check'],
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

                                    <?php elseif ($setting['tipe'] === 'image'): ?>
                                        <?php if (!empty($setting['setting_value'])): ?>
                                            <div class="mb-2">
                                                <img src="<?= base_url('uploads/pengaturan/' . esc($setting['setting_value'])) ?>"
                                                    style="max-height:80px;border-radius:.375rem" alt="Current">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="pengaturan_file[<?= esc($key) ?>]"
                                            accept="image/jpeg,image/png,image/webp,image/svg+xml">
                                        <div class="form-text">Upload baru untuk mengganti.</div>

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

<?= $this->endSection() ?>
