<?php

require_once 'init.php';
require_once 'functions.php';

if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['user_name'];
}

$notFound = "По вашему запросу ничего не найдено";

$search = trim($_GET['search']) ?? '';

if ($search) {
    $sqlCountLots = 'SELECT COUNT(Lots.id) as count
                         FROM Lots
                        WHERE MATCH(lot_name, lot_info) AGAINST(?) AND final_date > now()';

    $stmt = db_get_prepare_stmt($con, $sqlCountLots, [$search]);
    mysqli_stmt_execute($stmt);
    $resultCountLots = mysqli_stmt_get_result($stmt);
    $countLots = mysqli_fetch_all($resultCountLots, MYSQLI_ASSOC);

    $currentPage = $_GET['page'] ?? 1;
    $pageItemsLimit = 9;
    $offset = ($currentPage - 1) * $pageItemsLimit;
    $pagesCount = ceil($countLots[0]['count'] / $pageItemsLimit);
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
                   WHERE MATCH(lot_name, lot_info) AGAINST(?) AND final_date > now()
                   ORDER BY create_date DESC
                   LIMIT $pageItemsLimit
                  OFFSET $offset";

    $stmt = db_get_prepare_stmt($con, $sqlSearch, [$search]);
    mysqli_stmt_execute($stmt);
    $resultSearchLots = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($resultSearchLots, MYSQLI_ASSOC);
    $pageContent = include_template('search.php', [
        'categories' => $categories,
        'notFound' => $notFound,
        'items' => $items,
        'pagesCount' => $pagesCount,
        'pages' => $pages,
        'currentPage' => $currentPage
    ]);
} else {
    $pageContent = include_template('search.php', ['categories' => $categories, 'notFound' => $notFound]);
}

$layout_content = include_template('layout.php', [
    'title' => 'Результаты поиска',
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
