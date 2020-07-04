<?php

require_once 'functions.php';

$is_auth = rand(0, 1);

$user_name = 'Александр';

$con = mysqli_connect("localhost", "root", "_caberne55_S", "yeticave");
mysqli_set_charset($con, "utf8");

if ($con) {
    $sqlCat = 'SELECT * FROM Categories';
    if ($result = mysqli_query($con, $sqlCat)) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
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
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
