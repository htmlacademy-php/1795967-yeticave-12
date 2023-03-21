<?php

/**
 * @var $link
 * @var $config
 * @var $message
 * @var array $categories
 * @var  $pageTitle
 * @var  $userName
 */

require_once __DIR__ . "/bootstrap.php";

$lotsPerPage = $config['pagination']['lotsPerPage'];
$message = 'Все лоты категории ';

if (!in_array($_GET['id'], array_column($categories, 'id'))) {
    header('Location: /404.php');
}
$category = $_GET['id'];
$currentPageNumber = getCurrentPageNumber($_GET);
$itemsCount = getCountTotalLotsInCategory($link, $category);
$totalPagesCount = getTotalPagesCount($itemsCount, $lotsPerPage);
$lots = getLotsByCategory($link, $category, $lotsPerPage, $currentPageNumber);
$getParam = '&id=' . $category;

$menu = includeTemplate('menu.php', ['categories' => $categories]);

$pagination = includeTemplate('pagination.php', [
    'lotsPerPage'       => $lotsPerPage,
    'itemsCount'        => $itemsCount,
    'currentPageNumber' => $currentPageNumber,
    'totalPagesCount'   => $totalPagesCount,
    'lots'              => $lots,
    'getParam'          => $getParam,
]);

$pageContent = includeTemplate('all-lots.php', [
    'pagination'        => $pagination,
    'lots'              => $lots,
    'message'           => $message,
    'link'              => $link,
    'category'          => $category,
]);
$footer = includeTemplate('footer.php', ['menu' => $menu]);
$layoutContent = includeTemplate('layout.php', [
    'menu' => $menu,
    'footer' => $footer,
    'pageTitle'   => $pageTitle . ' | ' . getCategoryNameById($link, $category),
    'pageContent' => $pageContent,
]);

print ($layoutContent);
