<?php
require_once __DIR__ . '/bootstrap.php';
//require_once __DIR__ . '/functions/date.php';

/** @var mysqli $link */
/** @var array $config */
/** @var string $pageTitle */
/** @var int $isAuth */
/** @var string $userName */
/** @var array $categories */
/** @var array $lots */

$currentDate = date('Y-m-d H:i:s');

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

