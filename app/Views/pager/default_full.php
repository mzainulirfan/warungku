<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<?php if ($pager->getPageCount() > 1): ?>
    <nav class="app-pagination" aria-label="Navigasi halaman">
        <span class="pagination-info">
            Halaman <?= esc((string) $pager->getCurrentPageNumber()) ?> dari <?= esc((string) $pager->getPageCount()) ?>
        </span>

        <ul class="pagination">
            <li class="<?= $pager->hasPrevious() ? '' : 'disabled' ?>">
                <?php if ($pager->hasPrevious()): ?>
                    <a href="<?= esc($pager->getFirst(), 'attr') ?>" aria-label="Halaman pertama">Awal</a>
                <?php else: ?>
                    <span aria-disabled="true">Awal</span>
                <?php endif ?>
            </li>

            <li class="<?= $pager->hasPrevious() ? '' : 'disabled' ?>">
                <?php if ($pager->hasPrevious()): ?>
                    <a href="<?= esc($pager->getPrevious(), 'attr') ?>" aria-label="Halaman sebelumnya">Sebelumnya</a>
                <?php else: ?>
                    <span aria-disabled="true">Sebelumnya</span>
                <?php endif ?>
            </li>

            <?php foreach ($pager->links() as $link): ?>
                <li class="<?= $link['active'] ? 'active' : '' ?>">
                    <a href="<?= esc($link['uri'], 'attr') ?>" <?= $link['active'] ? 'aria-current="page"' : '' ?>>
                        <?= esc($link['title']) ?>
                    </a>
                </li>
            <?php endforeach ?>

            <li class="<?= $pager->hasNext() ? '' : 'disabled' ?>">
                <?php if ($pager->hasNext()): ?>
                    <a href="<?= esc($pager->getNext(), 'attr') ?>" aria-label="Halaman berikutnya">Berikutnya</a>
                <?php else: ?>
                    <span aria-disabled="true">Berikutnya</span>
                <?php endif ?>
            </li>

            <li class="<?= $pager->hasNext() ? '' : 'disabled' ?>">
                <?php if ($pager->hasNext()): ?>
                    <a href="<?= esc($pager->getLast(), 'attr') ?>" aria-label="Halaman terakhir">Akhir</a>
                <?php else: ?>
                    <span aria-disabled="true">Akhir</span>
                <?php endif ?>
            </li>
        </ul>
    </nav>
<?php endif ?>
