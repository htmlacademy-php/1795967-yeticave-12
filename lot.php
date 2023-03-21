<?php

/**
 * @var mysqli $link
 * @var string $error
 * @var array $categories
 * @var array $lots
 * @var string $pageTitle
 * @var string $userName
 */

require_once __DIR__ . '/bootstrap.php';

if (!is_numeric($_GET['id'])) {
    header('Location: /404.php');
    exit();
}

$id = ($_GET['id']);
$lot = getLot($link, $id);
$error = '';

if (empty($lot['id'])) {
    header('Location: /404.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = filterFormFields($_POST);
    $error = validateBetForm($link, $formData['cost'], $lot);
    if (empty($error)) {
        addBet($link, $_SESSION['user']['id'], $formData['cost'], $lot['id']);
        header('Location: /lot.php?id=' . $lot['id']);
        exit();
    }
}

$bets = getAllBetsOfLot($link, $lot['id']);

$menu = includeTemplate('menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('lot.php', [
    'error'       => $error,
    'link'        => $link,
    'bets'        => $bets,
    'lot'         => $lot,
]);

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'menu'        => $menu,
    'footer'      => $footer,
    'categories'  => $categories,
    'pageTitle'   => $pageTitle . ' | ' . $lot['title'],
    'pageContent' => $pageContent,
]);

print($layoutContent);


