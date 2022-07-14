<?php
/** @var mysqli $link */
/** @var array $config */

require_once __DIR__ . '/bootstrap.php';

$isAuth = rand(0, 1);
$pageTitle = 'Главная';
$userName = 'Александр';
$currentDate = date('Y-m-d H:i:s');


$categories = getCategories($link);

$lots = getLots($link);



/** @var array $categories */
/** @var array $lots */
$pageContent = includeTemplate
(
    'main.php',
    ['categories' => $categories, 'lots' => $lots, 'currentDate' => $currentDate]
);
$layoutContent = includeTemplate
(
    'layout.php',
    [
        'categories' => $categories,
        'lots' => $lots,
        'pageTitle' => $pageTitle,
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent
    ]
);
print($layoutContent);

