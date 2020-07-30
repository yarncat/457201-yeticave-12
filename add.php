<?php

require_once 'init.php';

$title = 'Добавление лота';

$pageContent = include_template('addlot.php', ['menu' => $menu, 'categories' => $categories]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $lot['author'] = $_SESSION['user']['id'];
    $required = ['lot_name', 'category', 'message', 'lot_rate', 'lot_step', 'lot_date'];

    foreach ($required as $field) {
        if (empty($lot[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if (($_FILES['image']['name'])) {
        $fileName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        $fileType = mime_content_type($tmpName);
        $fileSize = $_FILES['image']['size'];

        if ($fileSize > 2000000) {
            $errors['image'] = 'Максимальный размер файла: 2Мб';
        }
        if ($fileType === "image/jpeg" || $fileType === "image/png") {
            move_uploaded_file($tmpName, 'uploads/' . $fileName);
            $lot['image_link'] = 'uploads/' . $fileName;
        } else {
            $errors['image'] = 'Загрузите изображение в формате JPG или PNG';
        }
    } else {
        $errors['image'] = 'Загрузите изображение';
    }

    if (mb_strlen($lot['lot_name']) > 100) {
        $errors['lot_name'] = 'Внимание: максимальное количество символов в названии лота: 100';
    }

    if (mb_strlen($lot['message']) > 255) {
        $errors['message'] = 'Внимание: максимальное количество символов в описании лота: 255';
    }

    if ($lot['lot_rate'] && $lot['lot_rate'] < 1) {
        $errors['lot_rate'] = 'Внимание: цена должна быть числом больше 0';
    } elseif ($lot['lot_rate'] && !preg_match("/^[0-9 ]+$/", $lot['lot_rate'])) {
        $errors['lot_rate'] = 'Введите, пожалуйста, корректное целое число';
    }

    if ($lot['lot_step'] && !ctype_digit($lot['lot_step'])) {
        $errors['lot_step'] = 'Внимание: ставка должна быть целым числом больше 0';
    }

    if ($lot['lot_date'] && !is_date_valid($lot['lot_date'])) {
        $errors['lot_date'] = 'Указанная дата должна быть больше текущей минимум на 1 сутки<br />Введите, пожалуйста, дату в указанном формате : ГГГГ-ММ-ДД';
    }

    if (count($errors)) {
        $pageContent = include_template('addlot.php', [
            'menu' => $menu,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        $sqlAddLot = "INSERT INTO Lots (lot_name, cat_code, lot_info, start_price, step_rate, final_date, author, image_link)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $result = getPrepareStmt($connect, $sqlAddLot, $lot);

        if ($result) {
            $lotId = mysqli_insert_id($connect);
            header("Location: lot.php?id=" . $lotId);
        } else {
            print(mysqli_error($connect));
        }
    }
}

if (!isset($_SESSION['user'])) {
    $title = '403';
    $pageContent = include_template('403.php', ['menu' => $menu]);
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
