<?php

/**
 * @var $config
 * @var $link
 * @var $userWinner
 */

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

$email = $config['email'];

$lots = getLotsWithoutWinner($link);
if (!empty($lots)) {
    foreach ($lots as $lot) {
        $lastBet = getLastBetOfLot($link, $lot['lotId']);
        if (!empty($lastBet)) {
            $winner = addWinner($link, $lastBet['user_id'], $lastBet['lot_id']);
            if (!empty($winner)) {
                $userWinner = getWinner($link, $lastBet['user_id']) ?? null;
            }
            $textHtml = includeTemplate('email.php', [
                'lot'        => $lot,
                'userWinner' => $userWinner,
            ]);
            try {
                notifyWinner($userWinner, $textHtml, $email);
            } catch (TransportExceptionInterface $e) {
            }
        }
    }
}
