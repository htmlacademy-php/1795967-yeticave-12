<?php
/**
 * Устанавливает соединение с базой данных
 * @param array $conf настройки для подключения к базе данных
 * @return mysqli объект подключения к базе данных
 */
function dbConnect(array $conf): mysqli
{
    $link = mysqli_connect($conf['host'], $conf['user'], $conf['password'], $conf['database']);
    if (!$link) {
        exit("Невозможно подключиться к базе данных: " . mysqli_connect_error());
    }
    mysqli_set_charset($link, "utf8");
    return $link;
}

/**
 * Получение списка категорий
 * @param mysqli $link объект подключения к базе данных
 * @return array
 */

function getCategories(mysqli $link): array
{
    $categories_list = "SELECT * FROM categories";
    $result = mysqli_query($link, $categories_list);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка лотов
 * @param mysqli $link объект подключения к базе данных
 * @return array
 */

function getLots(mysqli $link): array
{
    $sql = "SELECT l.id, l.title, l.price, l.image, MAX(b.price) AS current_price, finish_date, c.name
FROM lots l
       JOIN categories c ON l.category_id = c.id
       LEFT JOIN bets b ON l.id = b.lot_id
GROUP BY l.id, l.finish_date
ORDER BY l.finish_date DESC
LIMIT 8";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение одного лота по id
 * @param mysqli $link объект подключения к базе данных
 * @param int $id
 * @return ?array
 */

function getLot(mysqli $link, int $id): ?array
{
    $sql = "SELECT l.*, c.name AS category
FROM lots l
       JOIN categories c ON l.category_id = c.id
WHERE l.id = $id";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_assoc($result);
}


function dbError(mysqli $link): void
{
    exit('Ошибка при выполнении запроса: ' . mysqli_error($link));
}


/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

