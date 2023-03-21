<?php

/** @var array $categories
 * @var array $lots
 * @var string $pageTitle
 * @var string $userName
 * @var mysqli $link
 */

require_once __DIR__ . '/bootstrap.php';

$userId = getUserIdFromSession();

$errors = [];
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = getLotFormData($_POST);
    $errors = validateLotForm($formData, $_FILES);

    if (empty($errors)) {
        $formData['image'] = uploadFile($_FILES);
        $formData['user_id'] = $userId;
        $id = addLot($link, $formData);
        header("Location: /lot.php?id=$id");
        exit();
    }
}
$menu = includeTemplate('menu.php', ['categories' => $categories]);

$pageContent = includeTemplate('add-lot.php', [
    'categories' => $categories,
    'errors'     => $errors,
    'formData'   => $formData,
]);

$footer = includeTemplate('footer.php', ['menu' => $menu]);

$layoutContent = includeTemplate('layout.php', [
    'menu'        => $menu,
    'footer'      => $footer,
    'pageTitle'   => $pageTitle . '| Добавить лот',
    'pageContent' => $pageContent,
]);

print($layoutContent);
