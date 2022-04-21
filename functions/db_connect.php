<?php
function dbConnect(array $conf): bool|mysqli|null
{
    $link = mysqli_connect($conf['host'], $conf['user'], $conf['password'], $conf['database']);
    if (!$link) {
        print("Невозможно подключиться к базе данных: " . mysqli_connect_error());
    }
    mysqli_set_charset($link, "utf8");
    return $link;
}
