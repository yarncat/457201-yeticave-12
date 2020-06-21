<?php
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

function formatSum ($price)
{
    $roundSum = ceil($price);
    $result = number_format($roundSum, 0, ",", " ") . " ₽";
    return $result;
}

function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function getDateRange($findate)
{
    $endDate = strtotime($findate);
    $nowDate = strtotime('now');
    $diffDate = $endDate - $nowDate;
    $hours = intval($diffDate / 3600);
    $arr[] = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = intval(($diffDate % 3600) / 60);
    $arr[] = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    return $arr;
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
