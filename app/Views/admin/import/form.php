<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= esc($back_url) ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Import <?= esc($module_name) ?></h4>
        <p class="text-muted small mb-0">Upload file Excel untuk menambah data secara massal</p>
    </div>
</div>

<?php if (session()->has('import_errors')): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <strong><i class="bi bi-exclamation-triangle me-1"></i>Beberapa baris gagal diimpor:</strong>
        <ul class="mb-0 mt-2 small">
            <?php foreach (session('import_errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Upload -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-upload me-1 text-primary"></i>Upload File Excel
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= esc($import_url) ?>" enctype="multipart/form-data" data-no-progress>
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="import_file" class="form-control" accept=".xlsx,.xls" required>
                        <div class="form-text">Format: <code>.xlsx</code> atau <code>.xls</code>. Maks. 5 MB.</div>
                    </div>

                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-info-circle me-1"></i>
                        Baris pertama adalah <strong>header</strong>. Baris kedua adalah contoh (akan dilewati jika diisi sesuai contoh template). Data dimulai dari baris ke-3 atau ke-2 jika Anda menghapus baris contoh.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-cloud-upload me-1"></i>Proses Import
                        </button>
                        <a href="<?= esc($back_url) ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Panduan & Download Template -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-file-earmark-excel me-1 text-success"></i>Download Template
            </div>
            <div class="card-body p-3">
                <p class="small text-muted mb-3">Unduh template Excel yang sudah berisi format kolom dan contoh data. Isi data mulai dari baris ke-3.</p>
                <a href="<?= esc($template_url) ?>" class="btn btn-success w-100 fw-semibold">
                    <i class="bi bi-download me-1"></i>Download Template <?= esc($module_name) ?>
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-list-check me-1 text-primary"></i>Kolom yang Dikenali
            </div>
            <div class="card-body p-3">
                <table class="table table-sm table-borderless mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Kolom</th>
                            <th>Wajib</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($columns_info as $col): ?>
                        <tr>
                            <td><code><?= esc($col['name']) ?></code></td>
                            <td>
                                <?php if ($col['required']): ?>
                                    <span class="badge text-bg-danger">Ya</span>
                                <?php else: ?>
                                    <span class="badge text-bg-light text-muted border">Opsional</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted"><?= esc($col['info']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
