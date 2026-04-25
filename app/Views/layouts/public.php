<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $__siteName  = setting('site_name')    ?? 'Website Sekolah';
    $__tagline   = setting('site_tagline') ?? '';
    $__logoPath  = setting('logo_path');
    $__logoUrl   = $__logoPath
        ? base_url('uploads/pengaturan/' . $__logoPath)
        : base_url('assets/images/logo-sekolah.png');

    $__ogTitle   = $og_title  ?? (isset($title) ? $title . ' — ' . $__siteName : $__siteName);
    $__ogDesc    = $og_desc   ?? ($meta_desc ?? $__tagline);
    $__ogImage   = $og_image  ?? $__logoUrl;
    $__ogType    = $og_type   ?? 'website';
    $__ogUrl     = current_url();
    ?>
    <meta name="description" content="<?= esc($__ogDesc) ?>">
    <title><?= esc(isset($title) ? $title . ' — ' : '') ?><?= esc($__siteName) ?></title>

    <!-- OpenGraph / Facebook -->
    <meta property="og:site_name"   content="<?= esc($__siteName) ?>">
    <meta property="og:type"        content="<?= esc($__ogType) ?>">
    <meta property="og:url"         content="<?= esc($__ogUrl) ?>">
    <meta property="og:title"       content="<?= esc($__ogTitle) ?>">
    <meta property="og:description" content="<?= esc($__ogDesc) ?>">
    <meta property="og:image"       content="<?= esc($__ogImage) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:locale"      content="id_ID">
    <?php if ($__ogType === 'article'): ?>
    <meta property="article:published_time" content="<?= esc($og_article_time ?? '') ?>">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="<?= ($og_image ?? null) ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title"       content="<?= esc($__ogTitle) ?>">
    <meta name="twitter:description" content="<?= esc($__ogDesc) ?>">
    <meta name="twitter:image"       content="<?= esc($__ogImage) ?>">
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
