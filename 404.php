<?php

/**
 * @var $categories
 * @var $pageTitle
 * @var $userName
 */

require_once __DIR__ . '/bootstrap.php';

header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");

$menu = includeTemplate('menu/menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('404.php');

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'footer'      => $footer,
    'menu'        => $menu,
    'pageTitle'   => $pageTitle . ' | 404',
    'pageContent' => $pageContent,
]);
print($layoutContent);
