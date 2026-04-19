<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Manajemen Pengguna</h4>
        <small class="text-muted">Kelola akun admin dan kontributor</small>
    </div>
    <a href="<?= admin_url('users/create') ?>" class="btn btn-primary btn-action">
        <i class="bi bi-person-plus me-1"></i><span class="d-none d-sm-inline">Tambah Pengguna</span>
    </a>
</div>

<!-- Mobile Cards (xs/sm) -->
<div class="d-md-none">
    <?php if (empty($users)): ?>
        <div class="text-center py-4 text-muted">Belum ada pengguna</div>
    <?php else: ?>
        <?php foreach ($users as $u): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <div class="fw-semibold"><?= esc($u['nama']) ?></div>
                            <small class="text-muted font-monospace"><?= esc($u['username']) ?></small>
                        </div>
                        <div class="d-flex flex-wrap gap-1 justify-content-end">
                            <?php if ($u['role'] === 'superadmin'): ?>
                                <span class="badge bg-danger">Superadmin</span>
                            <?php elseif ($u['role'] === 'admin'): ?>
                                <span class="badge bg-primary">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-info text-dark">Kontributor</span>
                            <?php endif; ?>
                            <?php if ($u['is_active']): ?>
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($u['email']): ?>
                        <small class="text-muted"><i class="bi bi-envelope me-1"></i><?= esc($u['email']) ?></small>
                    <?php endif; ?>
                    <?php if ($u['last_login_at']): ?>
                        <div><small class="text-muted"><i class="bi bi-clock me-1"></i><?= format_tanggal($u['last_login_at'], 'short') ?></small></div>
                    <?php endif; ?>
                </div>
                <?php if ($u['role'] !== 'superadmin'): ?>
                    <div class="card-footer bg-white border-top p-2 d-flex gap-2">
                        <a href="<?= admin_url('users/edit/' . $u['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <form method="post" action="<?= admin_url('users/toggle/' . $u['id']) ?>"
                            data-confirm="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> pengguna ini?" data-confirm-ok="Lanjutkan" data-confirm-class="btn-warning" data-confirm-type="warning">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                <i class="bi <?= $u['is_active'] ? 'bi-person-slash' : 'bi-person-check' ?>"></i>
                            </button>
                        </form>
                        <?php if ((int)$u['id'] !== (int)session('admin_id')): ?>
                            <form method="post" action="<?= admin_url('users/delete/' . $u['id']) ?>"
                                data-confirm="Hapus pengguna &quot;<?= esc($u['nama']) ?>&quot;? Tindakan ini tidak dapat dibatalkan." data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Desktop Table (md+) -->
<div class="d-none d-md-block">
<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Username</th>
                    <th class="d-none d-lg-table-cell">Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="d-none d-lg-table-cell">Terakhir Login</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengguna</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= esc($u['nama']) ?></div>
                                <small class="text-muted d-md-none"><?= esc($u['username']) ?></small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <code><?= esc($u['username']) ?></code>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <?= $u['email'] ? esc($u['email']) : '<span class="text-muted">—</span>' ?>
                            </td>
                            <td>
                                <?php if ($u['role'] === 'superadmin'): ?>
                                    <span class="badge bg-danger">Superadmin</span>
                                <?php elseif ($u['role'] === 'admin'): ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">Kontributor</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($u['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-lg-table-cell small text-muted">
                                <?= $u['last_login_at'] ? format_tanggal($u['last_login_at'], 'short') : '—' ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <?php if ($u['role'] !== 'superadmin'): ?>
                                        <a href="<?= admin_url('users/edit/' . $u['id']) ?>"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="<?= admin_url('users/toggle/' . $u['id']) ?>" class="d-inline"
                                            data-confirm="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> pengguna ini?" data-confirm-ok="Lanjutkan" data-confirm-class="btn-warning" data-confirm-type="warning">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                                title="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                <i class="bi <?= $u['is_active'] ? 'bi-person-slash' : 'bi-person-check' ?>"></i>
                                            </button>
                                        </form>
                                        <?php if ((int)$u['id'] !== (int)session('admin_id')): ?>
                                            <form method="post" action="<?= admin_url('users/delete/' . $u['id']) ?>" class="d-inline"
                                                data-confirm="Hapus pengguna &quot;<?= esc($u['nama']) ?>&quot;? Tindakan ini tidak dapat dibatalkan." data-confirm-ok="Ya, Hapus" data-confirm-class="btn-danger" data-confirm-type="danger">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?= $this->endSection() ?>
