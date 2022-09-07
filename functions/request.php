<?php
/**
 * Формирует массив с безопасными данными из формы лота
 * @param array $formData
 * @return array
 */
function   getLotFormData(array $formData): array
{
    $fields = ['title', 'description', 'name', 'price', 'step', 'finish_date',];
    $protectedFormData = [];
    foreach ($fields as $field) {
        $protectedFormData[$field] = $formData[$field] ?? null;
    }
    return $protectedFormData;

}

