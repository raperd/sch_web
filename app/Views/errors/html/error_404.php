<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f0f4f8; min-height: 100vh; display: flex; align-items: center; }
        .error-card { max-width: 540px; width: 100%; }
        .error-number { font-size: 7rem; font-weight: 900; line-height: 1; color: #1a5276; opacity: .12; user-select: none; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="error-card mx-auto text-center">
            <div class="error-number">404</div>
            <div class="mb-3 mt-n3">
                <i class="bi bi-compass text-primary" style="font-size:4rem;opacity:.5"></i>
            </div>
            <h1 class="h3 fw-bold mb-2">Halaman Tidak Ditemukan</h1>
            <p class="text-muted mb-4">
                Maaf, halaman yang kamu cari tidak ada atau telah dipindahkan.<br>
                <?php if (ENVIRONMENT !== 'production'): ?>
                    <small class="text-danger"><?= esc($message) ?></small>
                <?php endif; ?>
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="/" class="btn btn-primary px-4">
                    <i class="bi bi-house me-1"></i>Ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <p class="mt-4 text-muted small">
                Butuh bantuan? Hubungi kami di
                <a href="/profil" class="text-decoration-none">halaman kontak</a>.
            </p>
        </div>
    </div>
</body>
</html>
