<?php
/**
 * @var  $lots
 * @var  $categories
 * @var  $lotsPerPage
 * @var  $itemsCount
 * @var  $totalPagesCount
 * @var  $currentPageNumber
 * @var  $search
 * @var  $message
 * @var  $pagination
 */
?>

    <div class="container">
        <section class="lots">
            <h2><?= $message ?><span><?= getQuotesForString($search) ?></span></h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $lot['image'] ?>" width="350" height="260" alt="<?= $lot['title'] ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= $lot['category_name'] ?></span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php?id=<?= $lot['id'] ?>"><?= $lot['description'] ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= $lot['price'] ?><b class="rub">р</b></span>
                                </div>
                                <div class="lot__timer timer">
                                    16:54:12
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <?= $pagination ?>

    </div>


