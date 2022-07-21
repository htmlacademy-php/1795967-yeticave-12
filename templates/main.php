<?php
/** @var array $categories
 *@var array $lots
 *@var string $currentDate
 */
?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
        снаряжение.</p>
    <ul class="promo__list">
        <?php
        foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['code'] ?>">
                <a class="promo__link" href="../pages/all-lots.html"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['image'] ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot['name']) ?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>">
                            <?= htmlspecialchars($lot['title']) ?>
                        </a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= priceFormat($lot['price']) ?></span>
                        </div>

                        <?php $timeLeft = timeLeft($lot['finish_date'], $currentDate); ?>
                        <div
                            class="lot__timer timer <?php if ((int)$timeLeft[0] < 1): ?>  timer--finishing <?php endif; ?> ">
                            <?= implode(':',timeLeft($lot['finish_date'], $currentDate)) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>
</section>
