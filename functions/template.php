<?php
/**
 * @param string $finishDate
 * @param string $currentDate
 * @return string[]
 */
function timeLeft(string $finishDate, string $currentDate): array
{
    $diff = strtotime($finishDate) - strtotime($currentDate);
    if ($diff <= 0) {
        return ['00', '00'];

    }
    return  [
        str_pad(floor($diff / 3600), 2, "0", STR_PAD_LEFT),
        str_pad(floor(($diff % 3600) / 60), 2, "0", STR_PAD_LEFT)
    ];
}

/**
 * @param int $price
 * @return string
 */
function priceFormat(int $price): string
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function includeTemplate(string $name, array $data = []): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
