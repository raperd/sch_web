<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — <?= esc(setting('site_name') ?? 'Admin Panel') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1a2035 0%, #1a5276 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,.3); max-width: 420px; width: 100%; }
        .login-header { background: linear-gradient(135deg, #1a5276, #2e86c1); border-radius: 1rem 1rem 0 0; padding: 2rem; text-align: center; }
        .form-control { min-height: 48px; border-radius: .5rem; }
        .btn-login { min-height: 48px; border-radius: .5rem; font-weight: 600; font-size: 1rem; }
        .input-group-text { min-height: 48px; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="login-card card w-100">
        <div class="login-header">
            <i class="bi bi-mortarboard-fill text-warning fs-1 d-block mb-2"></i>
            <h5 class="text-white mb-0 fw-bold"><?= esc(setting('site_name') ?? 'Admin Panel') ?></h5>
            <small class="text-white-50">Panel Manajemen Konten</small>
        </div>
        <div class="card-body p-4">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 py-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('admin/login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" id="username"
                               class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                               value="<?= esc(old('username')) ?>"
                               placeholder="Masukkan username" autofocus required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password"
                               class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                               placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>
        </div>
        <div class="card-footer bg-light text-center text-muted small py-2 rounded-bottom">
            <a href="<?= site_url('/') ?>" class="text-decoration-none text-muted">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Website
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const inp  = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'bi bi-eye'; }
}
</script>
</body>
</html>
