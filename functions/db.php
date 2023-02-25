<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Функция установки соединения с базой данных
 * @param array $conf Настройки для подключения к базе данных
 * @return mysqli Ресурс соединения с базой данных
 */
function dbConnect(array $conf): mysqli
{
    $link = mysqli_connect(
        $conf['host'],
        $conf['user'],
        $conf['password'],
        $conf['database'],
    );

    if (!$link) {
        exit("Невозможно подключиться к базе данных: " . mysqli_connect_error());
    }

    mysqli_set_charset($link, "utf8");

    return $link;
}

/**
 * Функция вывода ошибки при отсутствии соединения с базой данных
 * @param mysqli $link
 * @return void
 */

#[NoReturn]
function dbError(mysqli $link): void
{
    exit('Ошибка при выполнении запроса: ' . mysqli_error($link));
}

/**
 * Функция получения данных пользователя по email
 * @param mysqli $link Ресурс соединения с базой данных
 * @param string $email Данные из поля email
 * @return array|null Данные пользователя
 */

function getUserByEmail(mysqli $link, string $email): ?array
{
    $sql = "SELECT * FROM users WHERE email = ?";

    $data = [$email];

    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        dbError($link);
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Функция получения данных пользователя по id
 * @param mysqli $link Ресурс соединения с базой данных
 * @param int $id Данные из поля формы
 * @return array|null Данные пользователя
 */

function getUserById(mysqli $link, int $id): ?string
{
    $sql = "SELECT name FROM users WHERE id = ?";

    $data = [$id];

    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        dbError($link);
    }

    return mysqli_fetch_assoc($result)['name'];
}

/**
 * Функция получения списка категорий
 * @param mysqli $link Ресурс соединения с базой данных
 * @return array Возвращает массив категорий
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
 * Функция получения названия выбранной категории
 * @var mysqli $link Ресурс соединения с базой данных
 * @var int $id Id категории
 * @return string Возвращает имя категории
 */

function getCategoryNameById(mysqli $link, int $id): string
{
    $sql = "SELECT name FROM categories WHERE id = $id";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_assoc($result)['name'];
}

/**
 * Получение списка лотов
 * @param mysqli $link Ресурс соединения с базой данных
 * @var int $lotsPerPage
 * @var int $currentPageNumber
 * @return array
 */

function getLots(mysqli $link, int $lotsPerPage, int $currentPageNumber): array
{
    $offset = ($currentPageNumber -1) * $lotsPerPage;
    $sql =
        "SELECT l.id, l.title, l.price, l.image, MAX(b.price) AS current_price, finish_date, c.name
        FROM lots l
        JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON l.id = b.lot_id
        WHERE l.finish_date > NOW()
        GROUP BY l.id, l.finish_date
        ORDER BY l.finish_date DESC
        LIMIT $lotsPerPage OFFSET $offset";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        dbError($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение одного лота по id
 * @param mysqli $link Объект подключения к базе данных
 * @param int $id
 * @return ?array
 */

function getLot(mysqli $link, int $id): ?array
{
    $sql =
        "SELECT l.*, c.name AS category
        FROM lots l
        JOIN categories c ON l.category_id = c.id
        WHERE l.id = $id";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        dbError($link);
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Функция добавления нового пользователя в базу
 * @param mysqli $link Ресурс соединения с базой данных
 * @param array $formData Данные из формы
 */

function addUser(mysqli $link, array $formData): void
{
    $sql = "INSERT INTO users (name, email, password, contact)
            VALUES (?, ?, ?, ?)";
    /** @var string $password */
    $data = [
        $formData['name'],
        $formData['email'],
        $formData['password'],
        $formData['contact'],

    ];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        dbError($link);
    }
}

/**
 * Функция определяет победителя лота
 * @param mysqli $link
 * @param int $user_id
 * @param int $lot_id
 * @return array|null Возвращает массив с данными о победителе или null
 */
function getUserWinner(mysqli $link, int $user_id, int $lot_id): ?array
{
    $result = addWinner($link, $user_id, $lot_id);
    if ($result) {
        return getWinner($link, $user_id);
    }
    return null;
}

/**
 * Функция возвращает истекшие лоты без победителя
 * @param mysqli $link Ресурс подключения к базе данных
 * @return array|null
 */

function getLotsWithoutWinner(mysqli $link): ?array
{
    $sql = "SELECT id AS lotId, title AS lotTitle, user_id AS lotAuthor FROM lots l
            WHERE finish_date <= NOW()
            AND winner_id IS NULL";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция подсчета количества найденных лотов по полю поиска
 * @param mysqli $link Ресурс соединения с базой данных
 * @param string $search Данные из формы поиска
 * @return int Возвращает число строк
 */

function getCountTotalFoundLotsFromSearch(mysqli $link, string $search): int
{
    $sql = "SELECT COUNT(*)
            FROM lots
            WHERE MATCH(title,description) AGAINST(? IN NATURAL LANGUAGE MODE)";

    $data = [$search];

    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
    return (int)mysqli_fetch_assoc($result)['COUNT(*)'];
}

/**
 * Функция подсчета количества найденных лотов в категории
 * @param mysqli $link Ресурс соединения с базой данных
 * @param int $id Id категории
 * @return int|null Возвращает число строк
 */

function getCountTotalLotsInCategory(mysqli $link, int $id): ?int
{
    $sql = "SELECT COUNT(*) FROM lots WHERE category_id = $id";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_assoc($result)['COUNT(*)'] ?? null;
}

/**
 * Функция добавляет в базу данных новую ставку пользователя
 * @param mysqli $link Ресурс соединения с базой данных
 * @param int $user Id пользователя
 * @param int $price Введенная сумма ставки
 * @param int $lot Id лота
 * @return void
 */

function addBet(mysqli $link, int $user, int $price, int $lot): void
{
    $sql = "INSERT INTO bets (date_create, price, user_id, lot_id) VALUES (?, ?, ?, ?)";
    $data = [
      date('Y-m-d H:i:s'),
      $price,
      $user,
      $lot
    ];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
}

/**
 * Функция возвращает все ставки пользователя
 * @var mysqli $link
 * @var int $id Id пользователя
 * @return array | null При наличии возвращает массив с данными о ставках
 */

function getAllMyBets(mysqli $link, int $id): ?array
{
    $sql = "SELECT b.price, b.date_create, l.title, l.image, l.id AS lot_id, l.finish_date, c.name AS category_name, l.winner_id, l.title
            FROM bets b
                     JOIN lots l ON l.id = b.lot_id
                     JOIN categories c ON c.id = l.category_id
            WHERE b.user_id = $id
            ORDER BY l.finish_date DESC;";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция возвращает последнюю ставку лота
 * @param mysqli $link Ресурс соединения с базой данных
 * @param int $lot id лота
 * @return array|null В случае успеха возвращает массив с последней ставкой лота
 */

function getLastBetOfLot(mysqli $link, int $lot): ?array
{
    $sql = "SELECT * FROM bets WHERE lot_id=? ORDER BY (date_create) DESC LIMIT 1";
    $data = [$lot];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
    return mysqli_fetch_assoc($result) ?? null;
}

/**
 * Функция возвращает данные победителя
 * @param mysqli $link
 * @param $id
 * @return array
 */
function getWinner(mysqli $link, $id): array
{
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_assoc($result);
}

/**
 * Функция добавляет победителя в базу данных
 * @param mysqli $link
 * @param int $user_id
 * @param int $lot_id
 * @return bool
 */

function addWinner(mysqli $link, int $user_id, int $lot_id): bool
{
    $sql = "UPDATE lots SET winner_id = $user_id WHERE id = $lot_id";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return true;
}

/**
 * Функция формирует список лотов для текущей страницы поиска
 * @param mysqli $link Ресурс Соединения с базой данных
 * @param string $search Данные из формы поиска
 * @param int $lotsPerPage Количество лотов на странице
 * $@param int $currentPageNumber Номер текущей страницы
 * @return array Возвращает список лотов
 */

function searchLots(mysqli $link, string $search, int $lotsPerPage, int $currentPageNumber): array
{
    $offset = ($currentPageNumber -1) * $lotsPerPage;

    $sql =
        "SELECT l.id, l.title, l.description, l.price, MAX(b.price) AS current_price, image,
                c.name AS category_name, l.finish_date, MATCH(l.title, l.description) AGAINST(? IN NATURAL LANGUAGE MODE) AS score
        FROM lots l
        JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON l.id = b.lot_id
        WHERE MATCH(l.title, l.description) AGAINST(? IN NATURAL LANGUAGE MODE)
        GROUP BY l.id
        LIMIT ? OFFSET ?";

    $data = [$search, $search, $lotsPerPage, $offset];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция формирует общее количество открытых лотов
 * @var mysqli $link Ресурс соединения с базой данных
 */

function getCountLotsOpened(mysqli $link): ?int
{
    $sql = "SELECT COUNT(*) FROM lots WHERE finish_date > NOW()";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        dbError($link);
    }
    return mysqli_fetch_assoc($result)['COUNT(*)'] ?? null;
}

/**
 * Функция формирует список лотов для выбранной категории
 * @param mysqli $link Ресурс Соединения с базой данных
 * @param int $category Id категории
 * @param int $lotsPerPage Количество лотов на странице
 * $@param int $currentPageNumber Номер текущей страницы
 * @return array Возвращает список лотов
 */

function getLotsByCategory(mysqli $link, int $category, int $lotsPerPage, int $currentPageNumber): array
{
    $offset = ($currentPageNumber -1) * $lotsPerPage;

    $sql =
        "SELECT l.id, l.title, l.description, l.price, MAX(b.price) AS current_price, image,
                c.name AS category_name, l.finish_date
        FROM lots l
        JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON l.id = b.lot_id
        WHERE c.id = ?
        GROUP BY l.id
        LIMIT ? OFFSET ?";

    $data = [$category, $lotsPerPage, $offset];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция возвращает все ставки лота
 * @param mysqli $link Ресурс соединения с базой данных
 * @param int $lot id лота
 * @return array|null  Массив со всеми ставками лота
 */

function getAllBetsOfLot(mysqli $link, int $lot): ?array
{
    $sql = "SELECT * FROM bets WHERE lot_id=? ORDER BY (date_create) DESC ";
    $data = [$lot];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция добавляет в базу данных новый лот
 * @param mysqli $link Ресурс соединения с базой данных
 * @param array $data
 * @return ?int В случае успеха, возвращает id добавленного лота
 */

function addLot(mysqli $link, array $data): ?int
{
    $sql = "INSERT INTO lots (date_create, title, description, image, price, finish_date, step, user_id, category_id)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $data = [
        date('Y:m:d H:i:s'),
        $data['title'],
        $data['description'],
        $data['image'],
        $data['price'],
        $data['finish_date'],
        $data['step'],
        $data['user_id'],
        $data['category_id'],
    ];

    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        exit('Ошибка: ' . mysqli_error($link));
    }
    return mysqli_insert_id($link);
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param $link mysqli Ресурс соединения с базой данных
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return mysqli_stmt Подготовленное выражение
 */

function db_get_prepare_stmt(mysqli $link, string $sql, array $data = []): mysqli_stmt
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
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
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

