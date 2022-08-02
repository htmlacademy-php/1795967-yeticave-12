<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/functions/date.php';

/** @var array $categories */
/** @var array $lots */
/** @var string $pageTitle */
/** @var int $isAuth */
/** @var string $userName */



$pageContent = includeTemplate
(
    'add-lot.php',
    ['categories' => $categories]
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



