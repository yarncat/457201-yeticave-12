<?php

require_once 'init.php';

$title = 'Результаты поиска';

$search = trim($_GET['search']) ?? '';

$notFound = "По вашему запросу ничего не найдено";

if ($search) {
    $sqlCountLots = "SELECT Lots.id
                       FROM Lots
                      WHERE MATCH(lot_name, lot_info) AGAINST(?)
                        AND final_date > now()";

    $stmt = db_get_prepare_stmt($connect, $sqlCountLots, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $countLots = mysqli_num_rows($result);

    $currentPage = $_GET['page'] ?? 1;
    $pageItemsLimit = 9;
    $offset = ($currentPage - 1) * $pageItemsLimit;
    $pagesCount = ceil($countLots / $pageItemsLimit);
    $pages = range(1, $pagesCount);

    $sqlSearch = "SELECT lot_name, image_link, start_price, final_date, rate, count, Lots.id, lot_info, category
                    FROM Lots
                         LEFT JOIN
                           (SELECT lot_id, date_rate, count(rate) AS count, MAX(rate) AS rate
                              FROM Rates
                             GROUP BY lot_id) Rates
                                ON Lots.id = Rates.lot_id
                         LEFT JOIN Categories
                                ON Lots.cat_code = Categories.id
                   WHERE MATCH(lot_name, lot_info) AGAINST(?)
                     AND final_date > now()
                   ORDER BY create_date DESC
                   LIMIT $pageItemsLimit
                  OFFSET $offset";

    $stmt = db_get_prepare_stmt($connect, $sqlSearch, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $lots = include_template('lots.php', ['items' => $items, 'notFound' => $notFound]);

    $pageContent = include_template('search.php', [
        'menu' => $menu,
        'lots' => $lots,
        'pages' => $pages,
        'pagesCount' => $pagesCount,
        'currentPage' => $currentPage
    ]);
} else {
    $lots = include_template('lots.php', ['notFound' => $notFound]);

    $pageContent = include_template('search.php', [
        'menu' => $menu,
        'lots' => $lots,
        'notFound' => $notFound
    ]);
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
