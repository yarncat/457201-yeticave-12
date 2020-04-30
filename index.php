<?php
$is_auth = rand(0, 1);

$user_name = 'Александр';

$itemCategory = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$items = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => '10999',
        'imageurl' => 'img/lot-1.jpg',
        'findate' => '2020-05-01'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => '159999',
        'imageurl' => 'img/lot-2.jpg',
        'findate' => '2020-05-02'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => '8000',
        'imageurl' => 'img/lot-3.jpg',
        'findate' => '2020-05-03'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => '10999',
        'imageurl' => 'img/lot-4.jpg',
        'findate' => '2020-05-04'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => '7500',
        'imageurl' => 'img/lot-5.jpg',
        'findate' => '2020-05-05'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => '5400',
        'imageurl' => 'img/lot-6.jpg',
        'findate' => '2020-05-06'
    ]
];

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

$pageContent = include_template('main.php', ['itemCategory' => $itemCategory, 'items' => $items]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'itemCategory' => $itemCategory
]);

print($layout_content);
?>
