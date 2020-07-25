<?php

require_once 'init.php';

$notFound = "Открытых лотов в этой категории нет";

$request = $_GET['id'];

foreach ($categories as $category) {
    if ($request === $category['id']) {
        $request = $category;
        $title = $category['category'];
        break;
    }
}

if ($_GET['id'] > 0 && $_GET['id'] <= count($categories)) {
    $currentCategory = $categories[$_GET['id'] - 1]['category'];

    $sqlCountLotsOnCategory = "SELECT Lots.id
                                 FROM Lots
                                      LEFT JOIN Categories
                                             ON Lots.cat_code = Categories.id
                                WHERE final_date > now()
                                  AND cat_code = {$_GET['id']}";

    $countLotsOnCategory = getNumRows($connect, $sqlCountLotsOnCategory);

    $currentPage = $_GET['page'] ?? 1;
    $pageLotsLimit = 9;
    $offset = ($currentPage - 1) * $pageLotsLimit;
    $pagesCount = ceil($countLotsOnCategory / $pageLotsLimit);
    $pages = range(1, $pagesCount);

    $sqlLotsByCategory = "SELECT Lots.id, lot_name, image_link, start_price, final_date, rate, count, category
                            FROM Lots
                                 LEFT JOIN
                                   (SELECT lot_id, date_rate, count(rate) AS count, MAX(rate) AS rate
                                      FROM Rates
                                     GROUP BY lot_id) Rates
                                        ON Lots.id = Rates.lot_id
                                 LEFT JOIN Categories
                                        ON Lots.cat_code = Categories.id
                           WHERE final_date > now()
                             AND Categories.id = {$_GET['id']}
                           ORDER BY create_date DESC
                           LIMIT $pageLotsLimit
                          OFFSET $offset";

    $items = getResultAsArray($connect, $sqlLotsByCategory);

    $lots = include_template('lots.php', ['items' => $items, 'notFound' => $notFound]);

    $pageContent = include_template('categories.php', [
        'lots' => $lots,
        'pages' => $pages,
        'pagesCount' => $pagesCount,
        'categories' => $categories,
        'currentPage' => $currentPage,
        'currentCategory' => $currentCategory
    ]);
} else {
    $title = '404';
    $pageContent = include_template('404.php', ['menu' => $menu]);
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
