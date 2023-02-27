<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 * @param string $date Дата в виде строки
 * @return bool  При совпадении с форматом 'ГГГГ-ММ-ДД' true, иначе false
 */

function isDateValid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}
