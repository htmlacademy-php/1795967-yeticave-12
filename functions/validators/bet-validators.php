<?php

/**
 * Функция проверки формы добавления ставки лота
 * @param mysqli $link Ресурс соединения с базой данных
 * @param string  $formData Данные из формы лота
 * @param array $lot Массив с данными лота
 * @return string|null Возвращает текст ошибки при наличии
 */
function validateBetForm(mysqli $link, string $formData, array $lot  ): ?string
{
    $lastBet = getLastBetOfLot($link, $lot['id']);
    $lastBet['price'] = $lastBet['price'] ?? $lot['price'];
    $bet = $lastBet['price'] + $lot['step'];

    if (!empty($lastBet['user_id']) && $lastBet['user_id'] === $_SESSION['user']['id']) {
        $error = 'Последняя ставка сделана текущим пользователем';
    }
    if (strtotime($lot['finish_date']) - time() < 0) {
        $error = 'Срок размещения лота истек';
    }
    if ((!is_numeric($formData))) {
        $error = 'Введите ставку';
    }
    if ($formData < $bet) {
        $error = "Ставка должна быть не менее $bet";
    }
    return $error ?? null;
}
