<?php
/**
 * @var $menu
 * @var $categories
 * @var $link
 * @var $pageTitle
 * @var $timeLeft
 */

require_once __DIR__ . '/bootstrap.php';

$userId = getUserIdFromSession();
$bets = getAllMyBets($link, $userId);

$menu = includeTemplate('menu/menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('my-bets.php', [
    'bets'   => $bets,
    'userId' => $userId,
]);

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'menu'        => $menu,
    'footer'      => $footer,
    'categories'  => $categories,
    'pageTitle'   => $pageTitle . ' | Мои ставки',
    'pageContent' => $pageContent,
]);

print($layoutContent);