<?php

/**
 * @var $link
 * @var $config
 * @var $pageTitle
 * @var $userName
 * @var $categories
 * @var $lots
 * @var $promoMenu
 * @var $menu
 * @var $lotsPerPage
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/get-winner.php';

$lotsPerPage = $config['pagination']['mainLotsPage'];

$currentPageNumber = getCurrentPageNumber($_GET);
$itemsCount = getCountLotsOpened($link);
$totalPagesCount = getTotalPagesCount($itemsCount, $lotsPerPage);
$lots = getLots($link, $lotsPerPage, $currentPageNumber);

$menu = includeTemplate('menu.php', ['categories' => $categories]);
$promoMenu = includeTemplate('promo_menu.php', ['categories' => $categories]);
$lotsList = includeTemplate('lots-list.php', [
    'lots'        => $lots,
    'link'        => $link,
]);

$pagination = includeTemplate('pagination.php', [
    'totalPagesCount' => $totalPagesCount,
    'currentPageNumber' => $currentPageNumber,
]);

$pageContent = includeTemplate('main.php', [
    'promoMenu' => $promoMenu,
    'lotsList'    => $lotsList,
    'pagination' => $pagination,
]);

$footer = includeTemplate('footer.php', ['categories' => $categories, 'menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
        'footer' => $footer,
        'pageTitle'   => $pageTitle . ' | Главная',
        'pageContent' => $pageContent,
    ]);
print($layoutContent);

