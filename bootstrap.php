<?php

const UPLOAD_DIR = __DIR__ . '/uploads';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Yekaterinburg');

require_once __DIR__ . '/functions/helpers.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/email.php';
require_once __DIR__ . '/functions/validators/lot-validators.php';
require_once __DIR__ . '/functions/validators/user-validators.php';
require_once __DIR__ . '/functions/validators/bet-validators.php';

$config = require __DIR__ . '/config.php';
$link = dbConnect($config['db']);
$pageTitle = $config['main']['name'];
$categories = getCategories($link);


