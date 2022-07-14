<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ .'/functions/validation.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/db.php';
$config = require __DIR__ . '/config.php';
$link = dbConnect($config['db']);
