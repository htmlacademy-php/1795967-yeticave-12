<?php
//require_once __DIR__ . '../../bootstrap.php';
//require_once '../../add.php';
/**
 * Проверяет форму лота
 * @param array $formData
 * @param mysqli $link
 * @return array массив ошибок(если массив пустой, то валидация прошла успешно)
 */
///** @var mysqli $link */
//$categories = getCategories($link);
function validateLotForm(array $formData, array $categories,  mysqli $link): array
{
    $errors = [];
    $errors['title'] = validateLotTitle($formData['title']);
    $errors['description'] = validateLotDescription($formData['description']);
    $errors['price'] = validateLotPrice($formData['price']);
    $errors['name'] = validateLotCategory($formData['name'],$categories);

    return array_filter($errors);
}

function validateLotTitle(?string $title): ?string
{
    if ($title === null || $title === '') {
        return 'Название лота обязательно для заполнения';
    }
    if (mb_strlen($title) > 128) {
        return 'Длина строки не может превышать 128 символов';
    }
    return null;
}

function validateLotDescription(?string $description): ?string
{
    if ($description === null || $description === '') {
        return 'Поле обязательно для заполнения';
    }
    if (mb_strlen($description) > 1000) {
        return 'Описание не может превышать 1000 символов';
    }
    return null;
}


function validateLotPrice(?string $price): ?string
{
    if ($price === null || $price === '') {
        return 'Поле обязательно для заполнения';
    }
    if (!is_numeric($price)) {
        return 'Некорректное значение';
    }
    if ((int)$price <= 0) {
        return 'Некорректная цена';
    }
    return null;
}
function validateLotCategory(?string $cat, array $categories): ?string
{
    $list_categories = [];
    foreach ($categories as $item) {
        $list_categories[] = $item['name'];
    }
    if (!in_array($cat, $list_categories, true)) {
        return 'Необходимо выбрать категорию';
    }
    return null;
}
//function validateLotCategory
//function validateLotDescription
//function validateLotPrice
//function validateLotStep
//function validateLotFinishDate
