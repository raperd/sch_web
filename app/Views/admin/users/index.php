<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Manajemen Pengguna</h4>
        <small class="text-muted">Kelola akun admin dan kontributor</small>
    </div>
    <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary btn-action">
        <i class="bi bi-person-plus me-1"></i><span class="d-none d-sm-inline">Tambah Pengguna</span>
    </a>
</div>

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
                                        <a href="<?= site_url('admin/users/edit/' . $u['id']) ?>"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="<?= site_url('admin/users/toggle/' . $u['id']) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                                title="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                                onclick="return confirm('<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> pengguna ini?')">
                                                <i class="bi <?= $u['is_active'] ? 'bi-person-slash' : 'bi-person-check' ?>"></i>
                                            </button>
                                        </form>
                                        <?php if ((int)$u['id'] !== (int)session('admin_id')): ?>
                                            <form method="post" action="<?= site_url('admin/users/delete/' . $u['id']) ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                    onclick="return confirm('Hapus pengguna <?= esc($u['nama']) ?>? Tindakan ini tidak dapat dibatalkan.')">
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

<?= $this->endSection() ?>
