<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Kegiatan</h4>
        <p class="text-muted small mb-0">Agenda dan kegiatan sekolah</p>
    </div>
    <a href="<?= admin_url('kegiatan/create') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-1"></i>Tambah
    </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <?php
    $stats = [
        ['Semua', $total_all, 'primary'],
        ['Upcoming', $total_upcoming, 'info'],
        ['Ongoing', $total_ongoing, 'success'],
        ['Selesai', $total_selesai, 'secondary'],
    ];
    foreach ($stats as [$lbl, $val, $cls]):
    ?>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-<?= $cls ?>"><?= $val ?></div>
                <small class="text-muted"><?= $lbl ?></small>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= admin_url('kegiatan') ?>" class="row g-2 align-items-end">
            <div class="col-sm-4 col-md-5">
                <label class="form-label form-label-sm">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="q" value="<?= esc($search) ?>" placeholder="Judul kegiatan...">
                </div>
            </div>
            <div class="col-sm-2">
                <label class="form-label form-label-sm">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php foreach (['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'selesai' => 'Selesai'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= $status_filter === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-2">
                <label class="form-label form-label-sm">Tipe</label>
                <select name="tipe" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php foreach (['event' => 'Event', 'lomba' => 'Lomba', 'sosial' => 'Sosial', 'osis' => 'OSIS', 'lainnya' => 'Lainnya'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= $tipe_filter === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="<?= admin_url('kegiatan') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Mobile Cards (xs/sm) -->
<div class="d-md-none">
    <?php if (!empty($kegiatan)): ?>
        <?php foreach ($kegiatan as $k): ?>
            <?php
            $statusMap = [
                'upcoming' => ['info', 'Upcoming'],
                'ongoing'  => ['success', 'Ongoing'],
                'selesai'  => ['secondary', 'Selesai'],
            ];
            [$kCls, $kLbl] = $statusMap[$k['status']] ?? ['secondary', $k['status']];
            ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex gap-2 mb-2">
                        <?php if (!empty($k['foto'])): ?>
                            <img src="<?= base_url('uploads/kegiatan/' . esc($k['foto'])) ?>"
                                class="rounded flex-shrink-0" style="width:52px;height:42px;object-fit:cover" alt="">
                        <?php endif; ?>
                        <div>
                            <div class="fw-semibold"><?= esc($k['judul']) ?></div>
                            <small class="text-muted">
                                <?= format_tanggal($k['tanggal'], 'short') ?>
                                <?php if (!empty($k['tanggal_selesai']) && $k['tanggal_selesai'] !== $k['tanggal']): ?>
                                    — <?= format_tanggal($k['tanggal_selesai'], 'short') ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge text-bg-<?= $kCls ?>"><?= $kLbl ?></span>
                        <span class="badge text-bg-light border"><?= ucfirst($k['tipe']) ?></span>
                        <?php if ($k['is_featured']): ?>
                            <span class="badge text-bg-warning"><i class="bi bi-star-fill me-1"></i>Unggulan</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-2 d-flex gap-2 justify-content-end">
                    <a href="<?= admin_url('kegiatan/edit/' . $k['id']) ?>"
                        class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="post" action="<?= admin_url('kegiatan/delete/' . $k['id']) ?>"
                        data-confirm="Hapus kegiatan ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar3 display-5 d-block mb-2 opacity-25"></i>
            Belum ada kegiatan. <a href="<?= admin_url('kegiatan/create') ?>">Tambah sekarang</a>
        </div>
    <?php endif; ?>
    <?php if (isset($pager)): ?>
        <div class="d-flex justify-content-center py-3">
            <?= $pager->links('kegiatan', 'default_full') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Desktop Table (md+) -->
<div class="d-none d-md-block">
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Kegiatan</th>
                    <th class="d-none d-md-table-cell">Tanggal</th>
                    <th class="d-none d-lg-table-cell">Tipe</th>
                    <th class="text-center">Status</th>
                    <th class="text-center d-none d-md-table-cell"><i class="bi bi-star-fill text-warning"></i></th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kegiatan)): ?>
                    <?php foreach ($kegiatan as $i => $k): ?>
                        <tr>
                            <td class="text-muted small"><?= $i + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($k['foto'])): ?>
                                        <img src="<?= base_url('uploads/kegiatan/' . esc($k['foto'])) ?>"
                                            class="rounded flex-shrink-0"
                                            style="width:44px;height:36px;object-fit:cover" alt="">
                                    <?php endif; ?>
                                    <div class="fw-semibold"><?= esc($k['judul']) ?></div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell text-muted small">
                                <?= format_tanggal($k['tanggal'], 'short') ?>
                                <?php if (!empty($k['tanggal_selesai']) && $k['tanggal_selesai'] !== $k['tanggal']): ?>
                                    <span class="text-muted">— <?= format_tanggal($k['tanggal_selesai'], 'short') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge text-bg-light border"><?= ucfirst($k['tipe']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusMap = [
                                    'upcoming' => ['info', 'Upcoming'],
                                    'ongoing'  => ['success', 'Ongoing'],
                                    'selesai'  => ['secondary', 'Selesai'],
                                ];
                                [$cls, $lbl] = $statusMap[$k['status']] ?? ['secondary', $k['status']];
                                ?>
                                <a href="<?= admin_url('kegiatan/edit/' . $k['id']) ?>"
                                    class="badge text-bg-<?= $cls ?> text-decoration-none"
                                    title="Klik edit untuk ubah status">
                                    <?= $lbl ?>
                                </a>
                            </td>
                            <td class="text-center d-none d-md-table-cell">
                                <?php if ($k['is_featured']): ?>
                                    <i class="bi bi-star-fill text-warning"></i>
                                <?php else: ?>
                                    <i class="bi bi-star text-muted"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?= admin_url('kegiatan/edit/' . $k['id']) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="post" action="<?= admin_url('kegiatan/delete/' . $k['id']) ?>"
                                        class="d-inline" data-confirm="Hapus kegiatan ini?" data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar3 display-5 d-block mb-2 opacity-25"></i>
                            Belum ada kegiatan.
                            <a href="<?= admin_url('kegiatan/create') ?>">Tambah sekarang</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager)): ?>
        <div class="card-footer bg-white border-top-0 d-flex justify-content-center py-3">
            <?= $pager->links('kegiatan', 'default_full') ?>
        </div>
    <?php endif; ?>
</div>
</div>

<?= $this->endSection() ?>
