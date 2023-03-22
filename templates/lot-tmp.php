<?php
/**
 * @var $link
 * @var $lot
 * @var $error
 * @var $bets
 */
?>

<section class="lot-item container">
    <h2><?= $lot['title'] ?></h2>
    <div class="lot-item__content">

        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category'] ?></span></p>
            <p class="lot-item__description"><?= $lot['description'] ?></p>
        </div>

        <div class="lot-item__right">
            <div class="lot-item__state">

                <?php $timeLeft = timeLeft($lot['finish_date']); ?>
                <div class="lot-item__timer timer <?= ((int)$timeLeft[0] < 1) ? 'timer--finishing' : ''?>">
                    <?= implode(':', $timeLeft) ?>
                </div>

                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <?php
                        $lastBet = getLastBetOfLot($link, $lot['id']); ?>
                        <?php if (empty($lastBet)): ?>
                            <span class="lot-item__amount">Стартовая цена</span>
                            <span class="lot-item__cost"><?= priceFormat($lot['price']) ?></span>
                        <?php else: ?>
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= priceFormat($lot['price'] = $lastBet['price']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= priceFormat($lot['price'] + $lot['step']) ?></span>
                    </div>
                </div>

                <?php if (isset($_SESSION['user']) && ($_SESSION['user']['id'] != $lot['user_id'])): ?>
                    <form class="lot-item__form" action="/lot.php?id=<?= $lot['id'] ?>" method="post"
                          autocomplete="off">
                        <p class="lot-item__form-item form__item <?= !empty($error) ? 'form__item--invalid' : null ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?= $lot['price'] + $lot['step'] ?>">
                            <span class="form__error"><?= $error ?? '' ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="history">
                <h3>История ставок (<span><?= count(getAllBetsOfLot($link, $lot['id'])) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets as $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= getUserById($link, $bet['user_id']) ?></td>
                            <td class="history__price"><?= $bet['price'] ?></td>
                            <td class="history__time"><?= pastDate($bet['date_create']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
