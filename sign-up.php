<?php

/**
 * @var array $categories
 * @var string $userName
 * @var string $pageTitle
 * @var mysqli $link Ресурс соединения с базой данных
 * @var array $pageContent
 */

require __DIR__ . '/bootstrap.php';

if (isset($_SESSION['user'])) {
    header('Location: /403');
}

$errors = [];
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = filterFormFields($_POST);
    $errors = validateRegistrationForm($link, $formData);

    if (!($errors)) {
        $formData['password'] = password_hash(($formData["password"] ?? ''), PASSWORD_DEFAULT);
        addUser($link, $formData);
        header("Location: /login.php");
        die();
    }
}

$menu = includeTemplate('menu/menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('sign-up.php', [
    'categories' => $categories,
    'formData'   => $formData,
    'errors'     => $errors,
]);

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'menu'        => $menu,
    'footer'      => $footer,
    'categories'  => $categories,
    'pageTitle'   => $pageTitle . '| Регистрация',
    'pageContent' => $pageContent,
]);

print($layoutContent);
