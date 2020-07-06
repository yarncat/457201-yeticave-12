<?php

require_once 'init.php';
require_once 'functions.php';

if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['user_name'];
}

if ($con) {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['cost'];
    foreach ($required as $field) {
        if (empty($required[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }
    $pageContent = include_template('lotinfo.php', [
        'errors' => $errors,
        'categories' => $categories,
        'lot' => $lot]);
} else {
    $pageContent = include_template('lotinfo.php', [
        'categories' => $categories,
        'lot' => $lot]);
}

$layout_content = include_template('layout.php', [
    'title' => $title,
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
