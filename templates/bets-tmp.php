
<?php
/**
* @var $bets
* @var $userId
 * @var $contact
*/
  ?>

<section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($bets as $bet): ?>
            <tr class="rates__item <?= ratesItemClass($bet['finish_date'], $userId, $bet['winner_id']) ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $bet['image'] ?>" width="54" height="40" alt="<?= $bet['title'] ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="../lot.php?id=<?= $bet['lot_id'] ?>"><?= $bet['title'] ?>></a></h3>
                        <p><?= ratesItemClass($bet['finish_date'], $userId, $bet['winner_id']) == 'rates__item--win' ? $contact : null ?></p>
                    </div>

                </td>
                <td class="rates__category">
                    <?= $bet['category_name'] ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?= timerClass($bet['finish_date'], $userId, $bet['winner_id']) ?>">
                        <?= timerResult($bet['finish_date'], $userId, $bet['winner_id']) ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= $bet['price'] ?>
                </td>
                <td class="rates__time">
                    <?= pastDate($bet['date_create']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
