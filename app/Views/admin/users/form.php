<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><?= $user ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h4>
        <p class="text-muted small mb-0"><?= $user ? esc($user['nama']) : 'Buat akun admin atau kontributor baru' ?></p>
    </div>
</div>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Periksa kembali:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="post"
    action="<?= $user ? base_url('admin/users/update/' . $user['id']) : base_url('admin/users/store') ?>">
    <?= csrf_field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold border-bottom">Informasi Pengguna</div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama"
                                value="<?= esc(old('nama', $user['nama'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username"
                                value="<?= esc(old('username', $user['username'] ?? '')) ?>"
                                autocomplete="username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="<?= esc(old('email', $user['email'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="admin" <?= old('role', $user['role'] ?? 'admin') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="kontributor" <?= old('role', $user['role'] ?? '') === 'kontributor' ? 'selected' : '' ?>>Kontributor</option>
                            </select>
                            <div class="form-text">Kontributor hanya dapat membuat dan mengedit artikel milik sendiri.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Password <?= $user ? '' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <input type="password" class="form-control" name="password"
                                autocomplete="new-password"
                                <?= $user ? '' : 'required' ?>>
                            <?php if ($user): ?>
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                            <?php else: ?>
                                <div class="form-text">Minimal 8 karakter.</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1"
                                    <?= old('is_active', $user['is_active'] ?? '1') == '1' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="isActive">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="bi bi-save me-1"></i><?= $user ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-1 text-primary"></i>Info Role</h6>
                    <dl class="small mb-0">
                        <dt class="text-muted">Admin</dt>
                        <dd class="mb-3">Akses penuh ke semua fitur pengelolaan konten.</dd>
                        <dt class="text-muted">Kontributor</dt>
                        <dd class="mb-0">Hanya dapat membuat artikel baru dan mengedit artikel milik sendiri. Tidak bisa mengubah pengaturan, galeri, atau data lainnya.</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
