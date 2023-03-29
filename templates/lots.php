<?php
/**
 * @var $link
 * @var $categories
 * @var $lots
 * @var $message
 * @var $category
 * @var $pagination
 */
?>

<div class="container">
    <section class="lots">
        <div class="lots__header">
            <h2><?= $message . getQuotesForString(getCategoryNameById($link, $category)) ?></h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>">
                        <div class="lot__image">
                            <img src="<?= $lot['image'] ?>" width="350" height="260" alt="">
                        </div>
                    </a>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot['title'] ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>">
                                <?= $lot['title'] ?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <?php $lastBet = getLastBetOfLot($link, $lot['id']);
                                if (!$lastBet): ?>
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= priceFormat($lot['price']) ?></span>
                                <?php else : ?>
                                    <span class="lot__amount">
                                    <?php $betsCount = count(getAllBetsOfLot($link, $lot['id']));
                                    print $betsCount . getNounPluralForm(
                                            $betsCount,
                                            ' ставка',
                                            ' ставки',
                                            ' ставок',
                                        ); ?>
                                </span>
                                    <span class="lot__cost"><?= priceFormat($lastBet['price']) ?></span>
                                <?php endif; ?>

                            </div>

                            <?php $timeLeft = timeLeft($lot['finish_date']); ?>
                            <div class="lot__timer timer
                            <?php if ((int)$timeLeft[0] < 1): ?>
                            timer--finishing
                            <?php endif; ?> ">
                                <?= implode(':', timeLeft($lot['finish_date'])) ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?= $pagination ?>

</div>
