<?php

/**
 * @var array $config
 * @var array $categories
 * @var string $pageTitle
 * @var  mysqli $link
 */

require_once __DIR__ . "/bootstrap.php";

if (empty($_GET['search'])) {
    $message = 'Введите запрос в строку поиска';
}

$lotsPerPage = $config['pagination']['lotsPerPage'];
$message = 'Результаты поиска по запросу ';

$search = filterSearchForm($_GET);
$currentPageNumber = getCurrentPageNumber($_GET);
$itemsCount = getCountTotalFoundLotsFromSearch($link, $search);
$totalPagesCount = getTotalPagesCount($itemsCount, $lotsPerPage);
$lots = searchLots($link, $search, $lotsPerPage, $currentPageNumber);
$getParam = '&search=' . $search;

if (!empty($search) && $itemsCount === 0) {
    $message = 'Ничего не найдено по запросу ';
}

$menu = includeTemplate('menu.php', ['categories' => $categories]);

$pagination = includeTemplate('pagination.php', [
    'lotsPerPage'       => $lotsPerPage,
    'itemsCount'        => $itemsCount,
    'currentPageNumber' => $currentPageNumber,
    'search'            => $search,
    'totalPagesCount'   => $totalPagesCount,
    'getParam'          => $getParam,
]);

$pageContent = includeTemplate('search-tmp.php', [
    'pagination' => $pagination,
    'message'    => $message,
    'search'     => $search,
    'categories' => $categories,
    'lots' => $lots,
]);

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'menu'        => $menu,
    'footer'      => $footer,
    'categories'  => $categories,
    'pageTitle'   => $pageTitle,
    'pageContent' => $pageContent,
]);

print ($layoutContent);
