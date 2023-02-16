<?php

/**
 * Функция возвращает оставшееся время до окончания размещения лота от текущего времени
 * @param string $finishDate Дата окончания размещения лота
 * @return array Возвращает массив [часы, минуты]
 */

function timeLeft(string $finishDate): array
{
    $diff = strtotime($finishDate) - time();
    if ($diff <= 0) {
        return ['00', '00'];
    }
    return [
        str_pad(floor($diff / 3600), 2, "0", STR_PAD_LEFT),
        str_pad(floor(($diff % 3600) / 60), 2, "0", STR_PAD_LEFT),
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
 * Функция возвращает строку с названием класса для подстановки в шаблон
 * @param string $finishDate Дата окончания размещения лота
 * @param int $Id Идентификатор пользователя
 * @param ?int $winnerId Идентификатор победителя, при наличии
 * @return ?string
 */

function timerClass(string $finishDate, int $Id, ?int $winnerId): ?string
{
    return match (true) {
        $Id === $winnerId => 'timer--win',
        strtotime($finishDate) <= time() => 'timer--end',
        (int)timeLeft($finishDate)[0] < 1 => 'timer--finishing',
        default => '',
    };
}

/**
 * Функция возвращает строку с названием класса для подстановки в шаблон
 * @param string $finishDate Дата окончания размещения лота
 * @param int $Id Идентификатор пользователя
 * @param ?int $winnerId Идентификатор победителя, при наличии
 * @return string | null
 */

function ratesItemClass(string $finishDate, int $Id, ?int $winnerId): ?string
{
    return match (true) {
        $Id === $winnerId => 'rates__item--win',
        strtotime($finishDate) <= time() => 'rates__item--end',
        default => '',
    };
}

/**
 * Функция возвращает строку с сообщением или время до окончания размещения лота
 * @param string $finishDate Дата окончания размещения лота
 * @param int $Id Идентификатор пользователя
 * @param ?int $winnerId Идентификатор победителя, при наличии
 * @return string
 */

function timerResult(string $finishDate, int $Id, ?int $winnerId): string
{
    return match (true) {
        $Id === $winnerId => 'Ставка выиграла',
        strtotime($finishDate) <= time() => 'Торги окончены',
//        strtotime($finishDate) > time() => timeLeft($finishDate),
        default => implode(':', timeLeft($finishDate)),
    };
}

/**
 * Функция возвращает id пользователя если авторизован,
 * иначе переадресовывает на 403
 * @return int | null
 */

function getUserIdFromSession(): ?int
{
    $userId = (int)$_SESSION['user']['id'];
    if (empty($userId)) {
        header('Location: 403.php');
        exit();
    }
    return $userId;
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

    return ob_get_clean();
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественного числа
 * j */
function getNounPluralForm(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    return match (true) {
        $mod100 >= 11 && $mod100 <= 20 => $many,
        $mod10 > 5 => $many,
        $mod10 === 1 => $one,
        $mod10 >= 2 && $mod10 <= 4 => $two,
        default => $many
    };
}

/**
 * Функция отображения даты создания ставки в удобочитаемом формате
 * @param string $dateCreate Дата создания ставки
 *
 * @return string Возвращает отформатированную строку с датой
 */

function pastDate(string $dateCreate): string
{
    $time = time() - strtotime($dateCreate);

    $hours = floor($time / 3600);
    $minutes = floor(($time % 3600) / 60);
    $past_date = date_create($dateCreate);

    return match (true) {
        $minutes < 1 => 'Только что',
        $hours < 1 => $minutes . ' ' . getNounPluralForm($minutes, 'минуту', 'минуты', 'минут') . ' ' . 'назад',
        ($hours >= 1) && ($hours < 24) => $hours . ' ' . getNounPluralForm(
                $hours,
                'час',
                'часа',
                'часов'
            ) . ' ' . 'назад',
        default => date_format($past_date, 'd.m.y в H:i')
    };
}

/**
 * Функция заключает текст в кавычки (текст => <<текст>>)
 * @param string $text Исходный текст
 * @return string Текст, заключенный в кавычки
 */

function getQuotesForString(string $text): string
{
    return $text ? $text = '«' . $text . '»' : '';
}
