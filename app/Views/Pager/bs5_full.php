<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * Bootstrap 5 pagination template — full (first/prev/pages/next/last).
 *
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Navigasi halaman">
    <ul class="pagination pagination-sm justify-content-center mb-0">

        <?php if ($pager->hasPrevious()): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="Halaman pertama">
                    <i class="bi bi-chevron-double-left"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><i class="bi bi-chevron-double-left"></i></span>
            </li>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><i class="bi bi-chevron-left"></i></span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <?php if ($link['active']): ?>
                    <span class="page-link"><?= $link['title'] ?></span>
                <?php else: ?>
                    <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                <?php endif ?>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Halaman terakhir">
                    <i class="bi bi-chevron-double-right"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><i class="bi bi-chevron-right"></i></span>
            </li>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><i class="bi bi-chevron-double-right"></i></span>
            </li>
        <?php endif ?>

    </ul>
</nav>
