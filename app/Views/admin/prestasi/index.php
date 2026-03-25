<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Prestasi</h4>
        <p class="text-muted small mb-0">Kelola pencapaian akademik dan non-akademik sekolah</p>
    </div>
    <a href="<?= base_url('admin/prestasi/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Prestasi
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary"><?= $total_all ?></div>
            <div class="text-muted small">Total Prestasi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success"><?= $total_akademik ?></div>
            <div class="text-muted small">Akademik</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning"><?= $total_non_akademik ?></div>
            <div class="text-muted small">Non-Akademik</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-danger"><?= $total_featured ?></div>
            <div class="text-muted small">Unggulan</div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="q" class="form-control form-control-sm"
                    placeholder="Cari judul prestasi..." value="<?= esc($search) ?>">
            </div>
            <div class="col-md-2">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <option value="akademik" <?= $kategori_filter === 'akademik' ? 'selected' : '' ?>>Akademik</option>
                    <option value="non_akademik" <?= $kategori_filter === 'non_akademik' ? 'selected' : '' ?>>Non-Akademik</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="tingkat" class="form-select form-select-sm">
                    <option value="">Semua Tingkat</option>
                    <?php foreach (['sekolah','kecamatan','kota_kabupaten','provinsi','nasional','internasional'] as $t): ?>
                        <option value="<?= $t ?>" <?= $tingkat_filter === $t ? 'selected' : '' ?>>
                            <?= ucwords(str_replace('_', ' ', $t)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="tahun" class="form-select form-select-sm">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($tahun_list as $t): ?>
                        <option value="<?= $t ?>" <?= $tahun_filter == $t ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="<?= base_url('admin/prestasi') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Prestasi</th>
                    <th>Kategori</th>
                    <th>Tingkat</th>
                    <th>Tahun</th>
                    <th>Siswa</th>
                    <th>Unggulan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prestasi)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-trophy fs-2 d-block mb-2 opacity-25"></i>
                            Belum ada data prestasi.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($prestasi as $p): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($p['foto'])): ?>
                                        <img src="<?= base_url('uploads/prestasi/' . esc($p['foto'])) ?>"
                                            class="rounded" style="width:44px;height:44px;object-fit:cover;" alt="">
                                    <?php else: ?>
                                        <div class="rounded bg-warning bg-opacity-15 d-flex align-items-center justify-content-center"
                                            style="width:44px;height:44px;">
                                            <i class="bi bi-trophy-fill text-warning"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold small"><?= esc($p['judul']) ?></div>
                                        <?php if (!empty($p['pembimbing'])): ?>
                                            <div class="text-muted" style="font-size:.75rem">
                                                <i class="bi bi-person me-1"></i><?= esc($p['pembimbing']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($p['kategori'] === 'akademik'): ?>
                                    <span class="badge text-bg-success">Akademik</span>
                                <?php else: ?>
                                    <span class="badge text-bg-warning text-dark">Non-Akademik</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $tingkatLabel = [
                                    'sekolah'         => ['Sekolah',        'secondary'],
                                    'kecamatan'       => ['Kecamatan',      'info'],
                                    'kota_kabupaten'  => ['Kota/Kab.',      'primary'],
                                    'provinsi'        => ['Provinsi',       'warning'],
                                    'nasional'        => ['Nasional',       'danger'],
                                    'internasional'   => ['Internasional',  'dark'],
                                ];
                                [$label, $color] = $tingkatLabel[$p['tingkat']] ?? [$p['tingkat'], 'secondary'];
                                ?>
                                <span class="badge text-bg-<?= $color ?>"><?= $label ?></span>
                            </td>
                            <td><?= esc($p['tahun']) ?></td>
                            <td class="text-muted small"><?= esc($p['nama_siswa'] ?? '-') ?></td>
                            <td>
                                <?= $p['is_featured']
                                    ? '<i class="bi bi-star-fill text-warning"></i>'
                                    : '<i class="bi bi-star text-muted"></i>' ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= base_url('admin/prestasi/edit/' . $p['id']) ?>"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="post" action="<?= base_url('admin/prestasi/delete/' . $p['id']) ?>"
                                        onsubmit="return confirm('Hapus prestasi ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pager): ?>
        <div class="card-footer border-0 bg-transparent">
            <?= $pager->links('prestasi', 'bootstrap_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
