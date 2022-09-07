<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/functions/validation.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/response.php';
require_once __DIR__ . '/functions/request.php';
require_once __DIR__ . '/functions/validators/lot-validators.php';
$config = require __DIR__ . '/config.php';
$link = dbConnect($config['db']);
$currentDate = date('Y-m-d H:i:s');
$isAuth = rand(0, 1);
$pageTitle = 'Главная';
$userName = 'Александр';
$currentDate = date('Y-m-d H:i:s');
$categories = getCategories($link);
$lots = getLots($link);

