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
    $sqlLot = 'SELECT lot_name, image_link, start_price, rate, final_date, Lots.id, category
                 FROM Lots
                      LEFT JOIN
                        (SELECT lot_id, date_rate, MAX(rate) AS rate
                           FROM Rates
                          GROUP BY lot_id) Rates
                             ON Lots.id = Rates.lot_id
                      LEFT JOIN Categories
                             ON Lots.cat_code = Categories.id
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
?>
