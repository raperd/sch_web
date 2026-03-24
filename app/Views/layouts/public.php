<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($meta_desc ?? setting('site_tagline') ?? '') ?>">
    <title><?= esc(isset($title) ? $title . ' — ' : '') ?><?= esc(setting('site_name') ?? 'Website Sekolah') ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>
<body>

<?= $this->include('templates/navbar') ?>

<main id="main-content">
    <?= $this->renderSection('content') ?>
</main>

<?= $this->include('templates/footer') ?>

<!-- Back to top -->
<button id="back-to-top" aria-label="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- App JS -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
