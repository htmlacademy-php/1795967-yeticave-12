<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/functions/date.php';

/** @var mysqli $link
 * @var string $currentDate
 */

/** @var array $categories */
/** @var array $lots */
/** @var string $pageTitle */
/** @var int $isAuth */
/** @var string $userName */

$id = $_GET['id'] ?? null;

if (empty($id)) {
    error(404, 'Лот не найден');
}
$lot = getLot($link, $id);
if (empty($lot)) {
    error(404, 'Лот не найден');
}
print_r($lot);

$pageContent = includeTemplate
(
    'lot.php',
    ['lot'=>$lot]
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


