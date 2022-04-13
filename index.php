<?php
/** @var mysqli $link */
require_once __DIR__ . '/bootstrap.php';
$isAuth = rand(0, 1);
$pageTitle = 'Главная';
$userName = 'Александр';
$currentDate = date('Y-m-d H:i:s');

if (!$link) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $categories_list = "SELECT * FROM categories";
    $result = mysqli_query($link, $categories_list);
    $lots_list = "SELECT l.title, l.price, l.image, MAX(b.price) as current_price, finish_date, c.name
FROM lots l
       JOIN categories c ON l.category_id = c.id
       LEFT JOIN bets b ON l.id = b.lot_id
GROUP BY l.id, l.finish_date
ORDER BY l.finish_date DESC
LIMIT 8";
    $res = mysqli_query($link, $lots_list);
    if ($res) {
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

$pageContent = includeTemplate
(
    'main.php',
    ['categories' => $categories, 'lots' => $lots, 'currentDate' => $currentDate]
);
$layoutContent = includeTemplate
(
    'layout.php',
    [
        'categories' => $categories, 'lots' => $lots, 'pageTitle' => $pageTitle,
        'isAuth' => $isAuth, 'userName' => $userName, 'pageContent' => $pageContent
    ]
);
print($layoutContent);

