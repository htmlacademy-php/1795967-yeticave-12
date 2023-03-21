<?php

/**
 * Функция валидации полей формы регистрации
 * @param mysqli $link Ресурс подключения к базе данных
 * @param  array $formData Данные из формы регистрации
 * @return array|null Возвращает массив с кодами ошибок
 */

function validateRegistrationForm(mysqli $link, array $formData): ?array
{
    $errors = [];
    $required = ['name', 'email', 'password', 'contact',];

    $errors['name'] = validateRegistrationName($formData['name']);
    $errors['email'] = validateRegistrationEmail($link, $formData['email']);
    $errors['password'] = validateRegistrationPassword($formData['password']);
    $errors['contact'] = validateRegistrationContact($formData['contact']);

    foreach ($required as $value) {
        if ($errors[$value]) {
            return $errors;
        }
        unset($errors[$value]);
}
    return null;
}

/**
 * Функция проверки заполнения поля имя
 * @var string $name Данные из формы
 * @return string|null Возвращает код ошибки при наличии
 */

function  validateRegistrationName(string $name): ?string
{
    if(empty($name)) {
        return 'Введите имя';
    }
    return null;
}

/**
 * Функция проверки введенного email
 * @var mysqli $link Объект подключения к базе данных
 * @var string $email Данные из поля email
 * @return string|null Возвращает код ошибки при наличии
 */

function validateRegistrationEmail(mysqli $link, string $email): ?string
{
    if (empty($email)) {
        return 'Введите ваш email';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный email';
    }

    $user = getUserByEmail($link, $email);
    if ($user) {
        return 'Пользователь с таким email уже существует';
    }
    return null;
}

/**
 * Функция проверки введенного пароля
 * @param string $password Данные из поля формы
 * @return string|null Возвращает код ошибки при наличии
 */

function validateRegistrationPassword(string $password): ?string
{
    if (empty($password)) {
        return 'Введите пароль';
    }

    if (strlen($password) > 128) {
        return 'Пароль не должен превышать 128 символов';
    }
    return null;
}

/**
 * Функция проверки валидности поля contact
 * @param string $contact Данные из формы регистрации
 * @return string|null Возвращает код ошибки при наличии
 */

function validateRegistrationContact(string $contact): ?string
{
    if (empty($contact)) {
        return 'Поле необходимо заполнить';
    }

    if (strlen($contact) > 256) {
        return 'Запись не должна превышать 256 символов';
    }
    return null;
}

/**
 * Функция проверки валидности формы регистрации
 * @param mysqli $link Ресурс подключения к базе данных
 * @param array $formData Данные из полей формы
 * @return array|null Возвращает массив с кодами ошибок при наличии
 */

function validateLoginForm(mysqli $link, array $formData): ?array
{
    $errors = [];
    $required = ['email', 'password'];

    $errors['email'] = validateLoginEmail($link, $formData['email']) ;
    $errors['password'] = validateLoginPassword($link, $formData['email'], $formData['password']) ;

    foreach ($required as $value) {
        if ($errors[$value]) {
            return $errors;
        }
        unset($errors[$value]);
    }
    return null;
}

/**
 * Функция проверки заполнения и уникальности поля email
 * @param string $email Данные из поля email
 * @param mysqli $link Ресурс подключения к базе данных
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLoginEmail(mysqli $link, string $email): ?string
{
    if (empty($email)) {
        return 'Введите ваш email';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный email';
    }

    $user = getUserByEmail($link, $email);
    if (!$user) {
        return 'Пользователь с таким email не найден';
    }
    return null;
}

/**
 * Функция проверки валидности поля password
 * @param mysqli $link Ресурс подключения к базе данных
 * @param string $password Данные из поля password
 * @param string $email Данные из поля email
 * @return string|null
 */

function validateLoginPassword(mysqli $link, string $email, string $password): ?string
{
    if (empty($password)) {
        return 'Введите пароль';
    }

    if (strlen($password) > 128) {
        return 'Пароль не должен превышать 128 символов';
    }

    $user = getUserByEmail($link, $email);
    if ($user) {
        if (!password_verify($password, $user['password'])) {
            return 'Вы ввели неверный пароль';
        }
    }
    return null;
}






















