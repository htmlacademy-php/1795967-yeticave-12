<?php

require_once __DIR__ . '/bootstrap.php';

/**
 * @var array $categories
 * @var string $userName
 * @var array $users
 * @var array $formData
 * @var mysqli $link
 * @var string $pageTitle
 */

$formData = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = filterFormFields($_POST);
    $errors = validateLoginForm($link, $formData);
$user = getUserByEmail($link, $formData['email']);
var_dump($user);
    if (empty($errors)) {
        $_SESSION['user'] = getUserByEmail($link, $formData['email']);
        header('Location: /');
        exit();
    }
}
$menu = includeTemplate('menu/menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('login.php', [
    'categories' => $categories,
    'formData'   => $formData,
    'errors'     => $errors,
]);
$footer = includeTemplate('footer.php', ['menu' => $menu]);
$layoutContent = includeTemplate('layout.php', [
    'footer'      => $footer,
    'menu'        => $menu,
    'pageContent' => $pageContent,
    'pageTitle'   => $pageTitle . ' | Вход',
]);

print($layoutContent);
