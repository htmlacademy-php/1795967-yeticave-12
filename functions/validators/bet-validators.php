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
    $errors = match (true) {
        !empty($lastBet['user_id']) && $lastBet['user_id'] === $_SESSION['user']['id'] => 'Последняя ставка сделана текущим пользователем',
        strtotime($lot['finish_date']) - time() < 0 => 'Срок размещения лота истек',
        (!is_numeric($formData)) => 'Введите ставку',
        $formData < $bet => "Ставка должна быть не менее $bet",
        default => null,
    };
    return $errors ?? null;
}
