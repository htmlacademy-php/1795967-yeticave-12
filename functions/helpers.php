<?php

/**
 * Функция подсчета общего количества в пагинации
 * @param int $itemsCount
 * @param int $lotsPerPage
 * @return int Возвращает общее количество страницы в пагинации
 */

function getTotalPagesCount(int $itemsCount, int $lotsPerPage): int
{
    return (int)ceil($itemsCount / $lotsPerPage);
}

/**
 * Функция загружает файл в папку 'uploads/' и возвращает ссылку на загруженный файл
 * @param array $file Массив с данными о файле
 * @return string Если файл успешно загружен, возвращает ссылку на загруженный файл
 */
function uploadFile(array $file): string
{
    $path = '';
    if (!empty($file['image']['name'])) {
        $file_name = $file['image']['name'];
        $file_temp = $file['image']['tmp_name'];
        $file_path = UPLOAD_DIR . '/' . $file_name;
        $file_status = move_uploaded_file($file_temp, $file_path);

        if ($file_status) {
            $path = 'uploads/' . $file_name;
        } else {
            exit('При загрузке файла, произошла критическая ошибка');
        }
    }
    return $path;
}

/**
 * Функция фильтрации данных из формы добавления лота
 * @param array $data Данные из формы
 * @return array Возвращает отфильтрованный массив с данными
 */

function getLotFormData(array $data): array
{
    $formData = filterFormFields($data);

    $formData['category_id'] = (int)$data['category_id'];
    $formData['price'] = (int)$data['price'];
    $formData['step'] = (int)$data['step'];

    return $formData;
}

/**
 * Функция фильтрации данных из формы добавления лота
 * @param array $data Данные из формы
 * @return array Возвращает отфильтрованный массив с данными
 */

function filterFormFields(array $data): array
{
    $dataFilter = [];
    foreach ($data as $key => $val) {
        $dataFilter[$key] = htmlspecialchars($val, ENT_QUOTES);
    }
    return $dataFilter;
}

/**
 * Функция фильтрации поля поиска
 * @param array $data Данные из формы поиска
 * @return string Возвращает отфильтрованную строку поля поиска
 */

function filterSearchForm(array $data): string
{
    $data = filterFormFields($data);
    return trim($data['search']);
}

/**
 * Функция возвращает номер текущей страницы
 * @param array $data Данные из GET параметров
 * @return int При отсутствии номера возвращает по умолчанию 1
 */

function getCurrentPageNumber(array $data): int
{
    return !empty($data['page']) ? (int)$data['page'] : 1;
}

/**
 * Функция возвращает оставшееся время до окончания размещения лота от текущего времени
 * @param string $finishDate Дата окончания размещения лота
 * @return array Возвращает массив [часы, минуты]
 */

function timeLeft(string $finishDate): array
{
    $time = [];
    $diff = strtotime($finishDate) - time();
    if ($diff <= 0) {
        $time = ['00', '00'];
    } else {
        $time[] = str_pad(floor($diff / 3600), 2, "0", STR_PAD_LEFT);
        $time[] = str_pad(floor(($diff % 3600) / 60), 2, "0", STR_PAD_LEFT);
    }
    return $time;
}

/**
 * Функция возвращает цену удобочитаемом формате
 * @param int $price Цена лота
 * @return string Строка в заданном формате
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
    $timerClass = '';

    if ($Id === $winnerId) {
        $timerClass = 'timer--win';
    }
    if (strtotime($finishDate) <= time()) {
        $timerClass = 'timer--end';
    }
    if ((int)timeLeft($finishDate)[0] < 1) {
        $timerClass = 'timer--finishing';
    }
    return $timerClass;
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
    $ratesItemClass = '';

    if (strtotime($finishDate) <= time()) {
        $ratesItemClass = 'rates__item--end';
    }
    if ($Id === $winnerId) {
        $ratesItemClass = 'rates__item--win';
    }
    return $ratesItemClass;
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
    $timerResult = implode(':', timeLeft($finishDate));
    if ($Id === $winnerId) {
        $timerResult = 'Ставка выиграла';
    }
    if (strtotime($finishDate) <= time()) {
        $timerResult = 'Торги окончены';
    }
    return $timerResult;
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

    if (is_readable($name)) {
        ob_start();
        extract($data);
        require $name;

        $result = ob_get_clean();
    }
    return $result;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
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
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 */
function getNounPluralForm(int $number, string $one, string $two, string $many): string
{
    $mod10 = $number % 10;
    $result = $many;

    if ($mod10 === 1) {
        $result = $one;
    }
    if ($mod10 >= 2 && $mod10 <= 4) {
        $result = $two;
    }
    return $result;
}

/**
 * Функция отображения даты создания ставки в удобочитаемом формате
 * @param string $dateCreate дата создания ставки
 * @return string возвращает отформатированную строку с датой
 */

function pastDate(string $dateCreate): string
{
    $time = time() - strtotime($dateCreate);

    $hours = floor($time / 3600);
    $minutes = floor(($time % 3600) / 60);
    $past_date = date_create($dateCreate);

    $result = date_format($past_date, 'd.m.y в h:i');

    if ($minutes < 1) {
        $result = 'только что';
    }
    if ($hours < 1) {
        $result = $minutes . ' ' . getNounPluralForm($minutes, 'минуту', 'минуты', 'минут') . ' ' . 'назад';
    }
    if (($hours >= 1) && ($hours < 24)) {
        $result = $hours . ' ' . getNounPluralForm($hours, 'час', 'часа', 'часов') . ' ' . 'назад';
    }
    return $result;
}

/**
 * Функция заключает текст в кавычки (текст => <<текст>>)
 * @param string $text исходный текст
 * @return string текст, заключенный в кавычки
 */

function getQuotesForString(string $text): string
{
    return ('«' . $text . '»') ?? '';
}
