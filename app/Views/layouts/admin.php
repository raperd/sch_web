<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> — <?= esc(setting('site_name') ?? 'Admin Panel') ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 56px;
            --bs-primary: #1a5276;
            --bs-primary-rgb: 26, 82, 118;
            --accent-gold: #d4ac0d;
            --touch-target: 48px;
            --sidebar-bg: #1a2035;
            --sidebar-text: #c8d0e0;
            --sidebar-active: #2e86c1;
        }

        body { background: #f0f2f5; font-size: 0.9rem; }

        /* ---- SIDEBAR ---- */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .25s ease;
        }
        #sidebar .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            text-decoration: none;
        }
        #sidebar .sidebar-brand img { height: 32px; margin-right: .5rem; }
        #sidebar .sidebar-brand span { color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.2; }
        #sidebar .nav-label {
            font-size: .65rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            padding: .75rem 1.25rem .25rem;
        }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: .6rem 1.25rem;
            border-radius: .375rem;
            margin: .1rem .5rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            min-height: var(--touch-target);
            font-size: .875rem;
            transition: background .15s, color .15s;
        }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        #sidebar .nav-link.active { background: var(--sidebar-active); color: #fff; }
        #sidebar .nav-link i { font-size: 1.05rem; width: 20px; text-align: center; }
        #sidebar .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,.08);
            padding: .75rem 1rem;
        }

        /* ---- TOPBAR ---- */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 1rem;
        }
        #topbar .btn-toggle { display: none; }

        /* ---- MAIN CONTENT ---- */
        #main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }
        .page-content { padding: 1.5rem; }

        /* ---- CARDS ---- */
        .stat-card { border: none; border-radius: .75rem; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .stat-card .card-body { padding: 1.25rem; }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: .5rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card .stat-number { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { color: #6b7280; font-size: .8rem; }

        /* ---- TABLE ---- */
        .table-card { border: none; border-radius: .75rem; box-shadow: 0 1px 4px rgba(0,0,0,.08); overflow: hidden; }
        .table-card .card-header { background: #fff; border-bottom: 1px solid #f3f4f6; padding: 1rem 1.25rem; }

        /* ---- FORMS (mobile-friendly) ---- */
        .form-control, .form-select { min-height: var(--touch-target); font-size: .9rem; }
        .btn-action { min-height: var(--touch-target); }

        /* ---- FLASH MESSAGES ---- */
        .flash-container { position: fixed; top: calc(var(--topbar-height) + .5rem); right: 1rem; z-index: 9999; min-width: 280px; }

        /* ---- MOBILE (<768px) ---- */
        @media (max-width: 767.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #topbar { left: 0; }
            #topbar .btn-toggle { display: flex; }
            #main-content { margin-left: 0; }
            .page-content { padding: 1rem; }
            .btn-action { display: block; width: 100%; margin-bottom: .5rem; }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>

<!-- ===================== SIDEBAR ===================== -->
<nav id="sidebar">
    <a href="<?= site_url('admin/dashboard') ?>" class="sidebar-brand">
        <?php if (setting('logo_path')): ?>
            <img src="<?= base_url('uploads/pengaturan/' . setting('logo_path')) ?>" alt="Logo">
        <?php else: ?>
            <i class="bi bi-mortarboard-fill text-warning fs-4 me-2"></i>
        <?php endif; ?>
        <span><?= esc(setting('site_name') ?? 'Admin Panel') ?></span>
    </a>

    <div class="overflow-y-auto flex-grow-1 py-2">
        <div class="nav-label">Utama</div>
        <a href="<?= site_url('admin/dashboard') ?>" class="nav-link <?= str_ends_with(current_url(), 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-label">Konten</div>
        <a href="<?= site_url('admin/artikel') ?>" class="nav-link <?= str_contains(current_url(), '/admin/artikel') ? 'active' : '' ?>">
            <i class="bi bi-newspaper"></i> Berita & Artikel
        </a>
        <a href="<?= site_url('admin/galeri') ?>" class="nav-link <?= str_contains(current_url(), '/admin/galeri') ? 'active' : '' ?>">
            <i class="bi bi-images"></i> Galeri
        </a>

        <div class="nav-label">Akademik</div>
        <a href="<?= site_url('admin/akademik/program') ?>" class="nav-link <?= str_contains(current_url(), '/admin/akademik/program') ? 'active' : '' ?>">
            <i class="bi bi-star"></i> Program Unggulan
        </a>
        <a href="<?= site_url('admin/akademik/kurikulum') ?>" class="nav-link <?= str_contains(current_url(), '/admin/akademik/kurikulum') ? 'active' : '' ?>">
            <i class="bi bi-journal-text"></i> Kurikulum
        </a>

        <div class="nav-label">Kesiswaan</div>
        <a href="<?= site_url('admin/kegiatan') ?>" class="nav-link <?= str_contains(current_url(), '/admin/kegiatan') ? 'active' : '' ?>">
            <i class="bi bi-calendar-event"></i> Kegiatan & Acara
        </a>
        <a href="<?= site_url('admin/ekskul') ?>" class="nav-link <?= str_contains(current_url(), '/admin/ekskul') ? 'active' : '' ?>">
            <i class="bi bi-trophy"></i> Ekstrakurikuler
        </a>
        <a href="<?= site_url('admin/prestasi') ?>" class="nav-link <?= str_contains(current_url(), '/admin/prestasi') ? 'active' : '' ?>">
            <i class="bi bi-award"></i> Prestasi
        </a>
        <a href="<?= site_url('admin/ppdb') ?>" class="nav-link <?= str_contains(current_url(), '/admin/ppdb') ? 'active' : '' ?>">
            <i class="bi bi-clipboard-check"></i> SPMB
        </a>

        <div class="nav-label">SDM</div>
        <a href="<?= site_url('admin/guru') ?>" class="nav-link <?= str_contains(current_url(), '/admin/guru') ? 'active' : '' ?>">
            <i class="bi bi-person-badge"></i> Guru & Staf
        </a>
        <a href="<?= site_url('admin/fasilitas') ?>" class="nav-link <?= str_contains(current_url(), '/admin/fasilitas') ? 'active' : '' ?>">
            <i class="bi bi-building"></i> Fasilitas
        </a>

        <div class="nav-label">Sistem</div>
        <a href="<?= site_url('admin/menu') ?>" class="nav-link <?= str_contains(current_url(), '/admin/menu') ? 'active' : '' ?>">
            <i class="bi bi-list-nested"></i> Manajemen Menu
        </a>
        <a href="<?= site_url('admin/pengaturan') ?>" class="nav-link <?= str_contains(current_url(), '/admin/pengaturan') ? 'active' : '' ?>">
            <i class="bi bi-gear"></i> Pengaturan Situs
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="<?= site_url('/') ?>" class="nav-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Lihat Website
        </a>
        <a href="<?= site_url('admin/logout') ?>" class="nav-link text-danger" onclick="return confirm('Yakin ingin logout?')">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</nav>

<!-- Overlay mobile -->
<div id="sidebar-overlay" class="d-none d-md-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="z-index:1039" onclick="toggleSidebar()"></div>

<!-- ===================== TOPBAR ===================== -->
<header id="topbar">
    <button class="btn btn-sm btn-outline-secondary btn-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
        <i class="bi bi-list fs-5"></i>
    </button>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="flex-grow-1 d-none d-md-block">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
            <?php if (isset($breadcrumb)): ?>
                <li class="breadcrumb-item active"><?= esc($breadcrumb) ?></li>
            <?php endif; ?>
        </ol>
    </nav>

    <div class="ms-auto d-flex align-items-center gap-2">
        <span class="d-none d-sm-inline text-muted small"><?= esc(session('admin_nama')) ?></span>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary rounded-circle p-1" data-bs-toggle="dropdown" aria-expanded="false" style="width:36px;height:36px">
                <i class="bi bi-person-circle fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><h6 class="dropdown-header"><?= esc(session('admin_nama')) ?></h6></li>
                <li><span class="dropdown-item-text text-muted small"><?= esc(session('admin_role')) ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= site_url('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- ===================== MAIN CONTENT ===================== -->
<main id="main-content">
    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-container">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-container">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="page-content">
        <?= $this->renderSection('content') ?>
    </div>
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const isOpen   = sidebar.classList.contains('show');
    sidebar.classList.toggle('show', !isOpen);
    overlay.classList.toggle('d-none', isOpen);
}

// Auto-dismiss flash after 4s
document.querySelectorAll('.flash-container .alert').forEach(el => {
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        if (bsAlert) bsAlert.close();
    }, 4000);
});
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
