<?php

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
    return array_map(function ($var) {
        return htmlspecialchars($var, ENT_QUOTES);
    }, $data);
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
 * @param array $data
 * @return int При отсутствии номера возвращает по умолчанию 1
 */

function getCurrentPageNumber(array $data): int
{
    if (empty($data['page'])) {
        return 1;
    }
    return (int) $data['page'];
}
