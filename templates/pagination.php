<?php
/**
 * @var $totalPagesCount
 * @var $currentPageNumber
 * @var $getParam
 * @var $search
 */
?>

<?php if ($totalPagesCount > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a class="<?= ($currentPageNumber === 1) ? 'page__item--hidden' : '' ?>"
               href="?page=<?= $currentPageNumber - 1 ?><?= $getParam ?? '' ?>">Назад
            </a>
        </li>
        <?php for ($i = 1; $i <= $totalPagesCount; $i++): ?>
            <li class="pagination-item <?= ($currentPageNumber === $i) ? 'pagination-item-active' : '' ?>">
                <a href="?page=<?= $i ?><?= $getParam ?? '' ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="pagination-item pagination-item-next">
            <a class="<?= ($currentPageNumber === $totalPagesCount) ? 'page__item--hidden' : '' ?>"
               href="?page=<?= ($currentPageNumber + 1) ?><?= $getParam ?? '' ?>">Вперед
            </a>
        </li>
    </ul>
<?php endif; ?>
