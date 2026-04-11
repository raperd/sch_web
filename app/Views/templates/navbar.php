<?php $menuModel = new \App\Models\MenuModel(); $menus = $menuModel->getPublicMenu(); ?>
<nav class="navbar navbar-main navbar-expand-lg fixed-top" id="mainNavbar">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
            <?php if (setting('logo_path')): ?>
                <img src="<?= base_url('uploads/pengaturan/' . setting('logo_path')) ?>" alt="Logo <?= esc(setting('site_name')) ?>">
            <?php else: ?>
                <i class="bi bi-mortarboard-fill text-primary fs-3"></i>
            <?php endif; ?>
            <div class="brand-text d-none d-sm-block">
                <div><?= esc(setting('site_name') ?? 'Nama Sekolah') ?></div>
                <div class="fw-normal text-muted" style="font-size:.7rem"><?= esc(setting('site_tagline') ?? '') ?></div>
            </div>
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <?php foreach ($menus as $menu): ?>
                    <?php
                        $isPpdb  = in_array(strtolower($menu['nama']), ['ppdb', 'spmb']);
                        $isActive = active_menu($menu['url']);
                    ?>
                    <li class="nav-item <?= $isPpdb ? 'nav-ppdb' : '' ?>">
                        <a class="nav-link <?= $isActive ?>"
                           href="<?= $menu['url'] === '/' ? site_url('/') : site_url(ltrim($menu['url'], '/')) ?>"
                           target="<?= esc($menu['target']) ?>">
                            <?php if ($menu['icon']): ?>
                                <i class="<?= esc($menu['icon']) ?> d-lg-none me-1"></i>
                            <?php endif; ?>
                            <?= esc($menu['nama']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- Spacer untuk fixed navbar -->
<div style="height: var(--nav-height, 70px)"></div>
