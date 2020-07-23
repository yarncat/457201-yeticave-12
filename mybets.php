<?php

require_once 'init.php';

$title = 'Мои ставки';

$dateNow = date("Y-m-d H:i:s");

$sqlWinners = "SELECT user_id, lot_id
                 FROM Rates
                      LEFT JOIN Lots
                             ON Rates.lot_id = Lots.id
                WHERE user_id = {$_SESSION['user']['id']}
                  AND final_date < now()
                  AND winner IS NULL";

$result = mysqli_query($connect, $sqlWinners);
$winners = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($winners as $winner) {
    $sqlAddWinner = "UPDATE Lots SET winner = {$winner['user_id']}
                      WHERE id = {$winner['lot_id']}";
    $result = mysqli_query($connect, $sqlAddWinner);
    if (!$result) {
        print(mysqli_error($connect));
    }
}

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

$result = mysqli_query($connect, $sqlMyRates);
$myRates = mysqli_fetch_all($result, MYSQLI_ASSOC);

$pageContent = include_template('mybets.php', [
    'menu' => $menu,
    'myRates' => $myRates,
    'dateNow' => $dateNow
]);

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
