<?php
function getCategories($link)
{
    $categories_list = "SELECT * FROM categories";
    $result = mysqli_query($link, $categories_list);
    if ($result) {
       return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

function getLots($link)
{
    $lots_list = "SELECT l.title, l.price, l.image, MAX(b.price) as current_price, finish_date, c.name
FROM lots l
       JOIN categories c ON l.category_id = c.id
       LEFT JOIN bets b ON l.id = b.lot_id
GROUP BY l.id, l.finish_date
ORDER BY l.finish_date DESC
LIMIT 8";
    $res = mysqli_query($link, $lots_list);
    if ($res) {
       return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
}



