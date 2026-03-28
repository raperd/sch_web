<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Konten PPDB</h4>
        <p class="text-muted small mb-0">Kelola informasi penerimaan peserta didik baru</p>
    </div>
    <a href="<?= base_url('admin/ppdb/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-1"></i>Tambah Konten
    </a>
</div>

<!-- Filter tipe -->
<div class="mb-4 d-flex gap-2 flex-wrap">
    <?php
    $tipes = ['' => 'Semua', 'persyaratan' => 'Persyaratan', 'jadwal' => 'Jadwal', 'alur' => 'Alur', 'faq' => 'FAQ', 'info' => 'Info'];
    foreach ($tipes as $val => $lbl):
    ?>
        <a href="<?= base_url('admin/ppdb' . ($val ? '?tipe=' . $val : '')) ?>"
            class="btn btn-sm <?= $tipe_filter === $val ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= $lbl ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Accordion per tipe -->
<?php
$tipeGroups = [];
foreach ($konten as $item) {
    $tipeGroups[$item['tipe']][] = $item;
}
$tipeLabels = ['persyaratan' => 'Persyaratan', 'jadwal' => 'Jadwal', 'alur' => 'Alur Pendaftaran', 'faq' => 'FAQ', 'info' => 'Info'];
$tipeColors = ['persyaratan' => 'primary', 'jadwal' => 'info', 'alur' => 'success', 'faq' => 'warning', 'info' => 'secondary'];
?>

<?php if (!empty($tipeGroups)): ?>
    <?php foreach ($tipeGroups as $tipe => $items): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold">
                    <span class="badge text-bg-<?= $tipeColors[$tipe] ?? 'secondary' ?> me-2"><?= $tipeLabels[$tipe] ?? $tipe ?></span>
                    <span class="text-muted small"><?= count($items) ?> blok</span>
                </span>
            </div>
            <!-- Mobile Cards -->
            <div class="d-md-none p-3">
                <?php foreach ($items as $item): ?>
                    <div class="d-flex align-items-start gap-2 py-2 border-bottom">
                        <div class="flex-grow-1" style="min-width:0">
                            <div class="fw-semibold"><?= esc($item['judul_blok']) ?></div>
                            <small class="text-muted"><?= truncate_text(strip_tags($item['konten']), 70) ?></small>
                        </div>
                        <span class="badge <?= $item['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?> flex-shrink-0">
                            <?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                        <a href="<?= base_url('admin/ppdb/edit/' . $item['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-shrink-0">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="post" action="<?= base_url('admin/ppdb/delete/' . $item['id']) ?>"
                            data-confirm="Hapus blok ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger flex-shrink-0">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Desktop Table -->
            <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul Blok</th>
                            <th class="text-center">Urutan</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= esc($item['judul_blok']) ?></div>
                                    <small class="text-muted"><?= truncate_text(strip_tags($item['konten']), 80) ?></small>
                                </td>
                                <td class="text-center"><?= $item['urutan'] ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $item['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                        <?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="<?= base_url('admin/ppdb/edit/' . $item['id']) ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="<?= base_url('admin/ppdb/delete/' . $item['id']) ?>"
                                            class="d-inline" data-confirm="Hapus blok ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-clipboard2-check display-5 d-block mb-2 opacity-25"></i>
            Belum ada konten PPDB.
            <a href="<?= base_url('admin/ppdb/create') ?>">Tambah sekarang</a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
