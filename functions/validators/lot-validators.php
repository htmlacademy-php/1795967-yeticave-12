<?php

/**
 * Функция валидации полей формы добавления лота
 * @param array $formData
 * @param array $files Массив с файлами
 * @return array Массив ошибок(если массив пустой, то валидация прошла успешно)
 */

function validateLotForm(array $formData, array $files): array
{
    $errors = [];
    $required = ['title', 'category_id', 'description', 'price', 'step', 'finish_date', 'image'];

    $errors['title'] = validateLotTitle($formData['title']);
    $errors['category_id'] = validateLotCategory($formData['category_id']);
    $errors['description'] = validateLotDescription($formData['description']);
    $errors['price'] = validateLotPrice($formData['price']);
    $errors['step'] = validateLotStep($formData['step']);
    $errors['finish_date'] = validateLotFinishDate($formData['finish_date']);
    $errors['image'] = validateLotFile($files);

    foreach ($required as $val) {
        if (empty($errors[$val])) {
            unset($errors[$val]);
        }
    }
    return array_filter($errors);
}

/**
 * Функция проверки имени лота
 * @param string|null $name Данные из формы лота
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLotTitle(?string $name): ?string
{
    if (empty($name)) {
        $error = 'Название лота обязательно для заполнения';
    }
    if (mb_strlen($name) > 128) {
        $error = 'Длина строки не может превышать 128 символов';
    }
    return $error ?? null;
}

/**
 * Функция проверки выбранной категории
 * @param string $id Id категории
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLotCategory(string $id): ?string
{
    if (empty($id)) {
        $error = 'Необходимо выбрать категорию';
    }
    return $error ?? null;
}

/**
 * Функция проверки заполнения описания лота
 * @param string|null $description Данные из формы добавления лота
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLotDescription(?string $description): ?string
{
    if (empty($description)) {
        $error = 'Поле обязательно для заполнения';
    }
    if (mb_strlen($description) > 1000) {
        $error = 'Описание не может превышать 1000 символов';
    }
    return $error ?? null;
}

/**
 * Функция проверки цены
 * @param int $price Данные из формы
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLotPrice(int $price): ?string
{
    if ($price <= 0) {
        $error = 'Введите начальную цену';
    }
    $_POST['price'] = $price;
    return $error ?? null;
}

/**
 * Функция проверки шага ставки лота
 * @param int $step Данные из формы
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLotStep(int $step): ?string
{
    if ($step <= 0) {
        $error = 'Введите шаг ставки';
    }
    $_POST['step'] = $step;
    return $error ?? null;
}

/**
 * Функция проверки даты завершения торгов лота
 * @param string $date Данные из формы
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLotFinishDate(string $date): ?string
{
    if (empty($date)) {
        $error = 'Введите дату завершения торгов';
    }
    if ((strtotime($date) - time()) < 43200) {
        $error = 'Дата должна быть больше одного дня';
    }
    return $error ?? null;
}

/**
 * Функция проверки загружаемого файла
 * @param array $file Данные добавленного файла
 * @return string|null Возвращает код ошибки, если он есть
 */
function validateLotFile(array $file): ?string
{
    $file_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $file_temp = $file['image']['tmp_name'];
    $file_error = $file['image']['error'];
    $file_name = $file['image']['name'];

    if (empty($file_name)) {
        $error = 'Добавьте изображение';
    }

    if ($file_error === 1) {
        $error = 'Размер файла слишком велик';
    }

    if (is_uploaded_file($file_temp)) {
        $file_type = mime_content_type($file_temp);
        if (!in_array($file_type, $file_types)) {
            $error = 'Неверный тип файла. Добавьте изображение в формате jpg или png';
        }
    }
    return $error ?? null;
}
