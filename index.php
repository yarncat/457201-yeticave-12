<?php

require_once 'init.php';

$title = 'Главная';

$notFound = 'Активных лотов на данный момент нет';

$sqlLots = 'SELECT Lots.id, lot_name, image_link, start_price, final_date, rate, count, category
              FROM Lots
                   LEFT JOIN
                     (SELECT lot_id, date_rate, count(rate) AS count, MAX(rate) AS rate
                        FROM Rates
                       GROUP BY lot_id) Rates
                          ON Lots.id = Rates.lot_id
                   LEFT JOIN Categories
                          ON Lots.cat_code = Categories.id
             WHERE final_date > now()
             ORDER BY create_date DESC';

$result = mysqli_query($connect, $sqlLots);
$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

$lots = include_template('lots.php', ['items' => $items, 'notFound' => $notFound]);

$pageContent = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
