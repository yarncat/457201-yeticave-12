<?php

require_once 'init.php';

$title = 'Мои ставки';

if (!$_SESSION) {
    header("Location: login.php");
}

$dateNow = date("Y-m-d H:i:s");

$sqlMyRates = "SELECT Lots.id, image_link, lot_name, category, final_date, rate, date_rate, user_contacts, winner
                FROM Lots
                     INNER JOIN Categories
                             ON Lots.cat_code = Categories.id
                     INNER JOIN Rates
                             ON Lots.id = Rates.lot_id
                     INNER JOIN Users
                             ON Lots.author = Users.id
               WHERE user_id = {$_SESSION['user']['id']}
               ORDER BY date_rate DESC";

$myRates = getResultAsArray($connect, $sqlMyRates);

if ($myRates) {
    $pageContent = include_template('mybets.php', [
        'menu' => $menu,
        'myRates' => $myRates,
        'dateNow' => $dateNow
    ]);
} else {
    $notFound = 'У вас пока ещё нет ставок';
    $pageContent = include_template('mybets.php', ['menu' => $menu, 'notFound' => $notFound]);
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
