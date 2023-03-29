<?php

/**
 * @var array $categories
 * @var string $pageTitle
 * @var string $userName
 */

require_once __DIR__ . '/bootstrap.php';

header("HTTP/1.1 403 Forbidden");
header("Status: 403 Forbidden");

$menu = includeTemplate('menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('403-error.php');

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'footer'      => $footer,
    'menu'        => $menu,
    'pageTitle'   => $pageTitle . ' | 403',
    'pageContent' => $pageContent,
]);

print($layoutContent);
