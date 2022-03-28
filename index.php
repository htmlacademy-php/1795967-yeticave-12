<?php
require_once __DIR__ . '/bootstrap.php';

$isAuth = rand(0, 1);
$pageTitle = 'Главная';
$userName = 'Александр';
$currentDate = date('Y-m-d H:i:s');

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'categories' => 'Доски и лыжи',
        'cost' => 10999,
        'url' => 'img/lot-1.jpg',
        'finishDate' => '2022-03-24'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'categories' => 'Доски и лыжи',
        'cost' => 15999,
        'url' => 'img/lot-2.jpg',
        'finishDate' => '2022-03-25'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'categories' => 'Крепления',
        'cost' => 8000,
        'url' => 'img/lot-3.jpg',
        'finishDate' => '2022-03-26'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'categories' => 'Ботинки',
        'cost' => 10999,
        'url' => 'img/lot-4.jpg',
        'finishDate' => '2022-03-27'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'categories' => 'Одежда',
        'cost' => 7500,
        'url' => 'img/lot-5.jpg',
        'finishDate' => '2022-03-28'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'categories' => 'Разное',
        'cost' => 5400,
        'url' => 'img/lot-6.jpg',
        'finishDate' => '2022-03-29'
    ]
];


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

