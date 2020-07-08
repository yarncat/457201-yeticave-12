<?php

require_once 'init.php';
require_once 'functions.php';

if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['user_name'];
}

if ($con) {
    $sqlLot = 'SELECT lot_name, image_link, start_price, final_date, rate, count, Lots.id, category
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
    if ($result = mysqli_query($con, $sqlLot)) {
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

$pageContent = include_template('main.php', ['categories' => $categories, 'items' => $items]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
