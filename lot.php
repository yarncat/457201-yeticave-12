<?php

require_once 'init.php';

$dateNow = date("Y-m-d H:i:s");

if ($_GET['id'] > 0) {
    $sqlLotInfo = "SELECT Lots.id, lot_name, image_link, rate, final_date, start_price, lot_info, step_rate, category, author, winner
                     FROM Lots
                          LEFT JOIN
                            (SELECT lot_id, MAX(rate) AS rate
                               FROM Rates
                              GROUP BY lot_id) Rates
                                 ON Lots.id = Rates.lot_id
                          LEFT JOIN Categories
                                 ON Lots.cat_code = Categories.id
                              WHERE Lots.id = {$_GET['id']}";

    $result = mysqli_query($connect, $sqlLotInfo);
    $lot = mysqli_fetch_assoc($result);
    $title = $lot['lot_name'];
    $nextRate = $lot['rate'] + $lot['step_rate'];
    $newRate = $lot['start_price'] + $lot['step_rate'];

    $sqlRatesOnLot = "SELECT rate, date_rate, lot_id, user_name, user_id
                        FROM Rates
                             INNER JOIN Users
                                ON Rates.user_id = Users.id
                             WHERE lot_id = {$_GET['id']}
                             ORDER BY date_rate DESC";

    $ratesOnLot = getResultAsArray($connect, $sqlRatesOnLot);
    $countRatesOnLot = getNumRows($connect, $sqlRatesOnLot);
    
    $lastRateUser = $countRatesOnLot ? $ratesOnLot[0]['user_id'] : null;

    if ($lot) {
        $pageContent = include_template('lotinfo.php', [
            'lot' => $lot,
            'menu' => $menu,
            'dateNow' => $dateNow,
            'newRate' => $newRate,
            'nextRate' => $nextRate,
            'ratesOnLot' => $ratesOnLot,
            'lastRateUser' => $lastRateUser,
            'countRatesOnLot' => $countRatesOnLot
        ]);
    } else {
        $title = '404';
        $pageContent = include_template('404.php', ['menu' => $menu]);
    }
} else {
    $title = '404';
    $pageContent = include_template('404.php', ['menu' => $menu]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rate = $_POST;

    if (empty($rate['cost'])) {
        $errors['cost'] = 'Введите вашу ставку';
    } elseif ($rate['cost'] && !ctype_digit($rate['cost'])) {
        $errors['cost'] = 'Ставка должна быть не меньше минимальной,  целым числом больше 0';
    } elseif ($rate['cost'] && ($rate['cost'] < $newRate || $rate['cost'] < $nextRate)) {
        $errors['cost'] = 'Введите ставку не меньше минимальной указанной';
    }

    if (isset($errors['cost'])) {
        $pageContent = include_template('lotinfo.php', [
            'lot' => $lot,
            'menu' => $menu,
            'errors' => $errors,
            'dateNow' => $dateNow,
            'newRate' => $newRate,
            'nextRate' => $nextRate,
            'ratesOnLot' => $ratesOnLot,
            'lastRateUser' => $lastRateUser,
            'countRatesOnLot' => $countRatesOnLot
        ]);
    } else {
        $sqlCheckRate = "SELECT *
                           FROM Rates
                          WHERE user_id = {$_SESSION['user']['id']}
                            AND lot_id = {$_GET['id']}";

        $checkRate = getResultAsArray($connect, $sqlCheckRate);
        $countRates = getNumRows($connect, $sqlCheckRate);

        if ($countRates) {
            $sqlDeleteLastRate = "DELETE
                                    FROM Rates
                                   WHERE lot_id = {$_GET['id']}
                                     AND user_id = {$_SESSION['user']['id']}";

            $result = mysqli_query($connect, $sqlDeleteLastRate);
        }

        $newRate = [$_GET['id'], $_SESSION['user']['id'], $_POST['cost']];
        $sqlAddRate = 'INSERT INTO Rates (lot_id, user_id, rate)
                       VALUES (?, ?, ?)';

        $result = getPrepareStmt($connect, $sqlAddRate, $newRate);

        if ($result) {
            header("Location: lot.php?id={$_GET['id']}");
        } else {
            print(mysqli_error($connect));
        }
    }
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
