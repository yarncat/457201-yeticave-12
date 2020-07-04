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

    $sqlLotInfo = 'SELECT lot_name, image_link, rate, final_date, start_price, Lots.id, lot_info, step_rate, category
                     FROM Lots
                          LEFT JOIN
                            (SELECT lot_id, MAX(rate) AS rate
                               FROM Rates
                              GROUP BY lot_id) Rates
                                 ON Lots.id = Rates.lot_id
                          LEFT JOIN Categories
                                 ON Lots.cat_code = Categories.id';

    if ($result = mysqli_query($con, $sqlLotInfo)) {
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $lot = null;

    if (isset($_GET['id'])) {
        $lot = $_GET['id'];
        foreach ($items as $item) {
            if ($lot === $item['id']) {
                $lot = $item;
                $title = $item['lot_name'];
                break;
            }
        }
        if (!is_array($lot)) {
            $lot = null;
            $title = '404';
        }
    }

    if (!$lot) {
        http_response_code(404);
    }
}

$pageContent = include_template('lotinfo.php', [
    'categories' => $categories,
    'lot' => $lot]);

$layout_content = include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
