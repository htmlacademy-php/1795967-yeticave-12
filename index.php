<?php
require_once 'helpers.php';
$is_auth = rand(0, 1);
$page_title = 'Главная';
$user_name = 'Александр'; // укажите здесь ваше имя

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [['title' => '2014 Rossignol District Snowboard', 'categories' => 'Доски и лыжи', 'cost' => 10999, 'url' => 'img/lot-1.jpg', 'finish_date' => '2022-03-24'],
    ['title' => 'DC Ply Mens 2016/2017 Snowboard', 'categories' => 'Доски и лыжи', 'cost' => 15999, 'url' => 'img/lot-2.jpg', 'finish_date' => '2022-03-25'],
    ['title' => 'Крепления Union Contact Pro 2015 года размер L/XL', 'categories' => 'Крепления', 'cost' => 8000, 'url' => 'img/lot-3.jpg', 'finish_date' => '2022-03-26'],
    ['title' => 'Ботинки для сноуборда DC Mutiny Charocal', 'categories' => 'Ботинки', 'cost' => 10999, 'url' => 'img/lot-4.jpg', 'finish_date' => '2022-03-27'],
    ['title' => 'Куртка для сноуборда DC Mutiny Charocal', 'categories' => 'Одежда', 'cost' => 7500, 'url' => 'img/lot-5.jpg', 'finish_date' => '2022-03-28'],
    ['title' => 'Маска Oakley Canopy', 'categories' => 'Разное', 'cost' => 5400, 'url' => 'img/lot-6.jpg', 'finish_date' => '2022-03-29']];

function timeLeft($finish_date): string
{
    $diff = strtotime($finish_date) - strtotime("now");
    return implode(':', [str_pad(floor($diff / 3600), 2, "0", STR_PAD_LEFT), str_pad(floor(($diff % 3600) / 60), 2, "0", STR_PAD_LEFT)]);
}

function price_format($price): string
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'user_name' => $user_name, 'page_title' => $page_title]);


print($layout_content);
