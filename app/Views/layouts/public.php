<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($meta_desc ?? setting('site_tagline') ?? '') ?>">
    <title><?= esc(isset($title) ? $title . ' — ' : '') ?><?= esc(setting('site_name') ?? 'Website Sekolah') ?></title>
    <?php $faviconPath = setting('favicon_path'); ?>
    <link rel="icon" type="image/x-icon" href="<?= $faviconPath ? base_url('uploads/pengaturan/' . esc($faviconPath)) : base_url('assets/images/logo-sekolah.png') ?>">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <?php
    $tp  = setting('tema_primary')   ?: '#1a5276';
    $ts  = setting('tema_secondary') ?: '#2e86c1';
    $ta  = setting('tema_accent')    ?: '#d4ac0d';
    // Convert hex to rgb for Bootstrap rgb variables
    $hexToRgb = fn($h) => implode(', ', array_map('hexdec', str_split(ltrim($h,'#'),2)));
    ?>
    <style>
        :root {
            --bs-primary: <?= esc($tp) ?>;
            --bs-primary-rgb: <?= $hexToRgb($tp) ?>;
            --site-secondary: <?= esc($ts) ?>;
            --site-secondary-rgb: <?= $hexToRgb($ts) ?>;
            --accent-gold: <?= esc($ta) ?>;
            --accent-gold-rgb: <?= $hexToRgb($ta) ?>;
        }
    </style>

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
