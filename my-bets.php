<?php
/**
 * @var $menu
 * @var $categories
 * @var $link
 * @var $pageTitle
 * @var $timeLeft
 * @var $contact
 */

require_once __DIR__ . '/bootstrap.php';

$userId = getUserIdFromSession();
$bets = getAllMyBets($link, $userId);
$contact = getUserContactById($link, $userId);

$menu = includeTemplate('menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('bets-tmp.php', [
    'bets'    => $bets,
    'userId'  => $userId,
    'contact' => $contact,
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
