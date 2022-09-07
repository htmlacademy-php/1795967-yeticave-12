<?php
require_once __DIR__ . '/bootstrap.php';

/** @var array $categories */
/** @var array $lots */
/** @var string $pageTitle */
/** @var int $isAuth */
/** @var string $userName */
/** @var mysqli $link */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = getLotFormData($_POST);
    $errors = validateLotForm($formData, $categories, $link);
}

    var_dump($errors);
    var_dump($formData);


$pageContent = includeTemplate
(
    'add-lot.php',
    ['categories' => $categories,
     'errors'=> $errors ?? [],
     'formData'=> $formData ?? [],
    ]
);

$layoutContent = includeTemplate
(
    'layout.php',
    [
        'categories'  => $categories,
        'lots'        => $lots,
        'pageTitle'   => $pageTitle,
        'isAuth'      => $isAuth,
        'userName'    => $userName,
        'pageContent' => $pageContent,
    ]
);
print($layoutContent);



