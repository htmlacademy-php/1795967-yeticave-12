<?php

/**
 * Функция подсчета общего количества в пагинации
 * @param int $itemsCount
 * @param int $lotsPerPage
 * @return int Возвращает общее количество страницы в пагинации
 */

function getTotalPagesCount(int $itemsCount, int $lotsPerPage): int
{
    return (int)ceil($itemsCount / $lotsPerPage);
}
