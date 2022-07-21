<?php

/** @var mysqli $link */

$currentDate = date('Y-m-d H:i:s');
$isAuth = rand(0, 1);
$pageTitle = 'Главная';
$userName = 'Александр';
$currentDate = date('Y-m-d H:i:s');
$categories = getCategories($link);
$lots = getLots($link);
