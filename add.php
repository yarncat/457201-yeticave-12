<?php

require_once 'init.php';
require_once 'functions.php';

$title = '403';

if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['user_name'];
    $title = 'Добавление лота';
    $pageContent = include_template('addlot.php', ['categories' => $categories]);

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
            $file_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $file_type = mime_content_type($tmp_name);
            $file_size = $_FILES['image']['size'];
            if ($file_size > 1000000) {
                $errors['image'] = 'Максимальный размер файла: 1Мб';
            }
            if ($file_type === "image/jpeg" || $file_type === "image/png") {
                move_uploaded_file($tmp_name, 'uploads/' . $file_name);
                $lot['image_link'] = 'uploads/' . $file_name;
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
                'errors' => $errors,
                'categories' => $categories]);
        } else {
            $sql = 'INSERT INTO lots (lot_name, cat_code, lot_info, start_price, step_rate, final_date, author, image_link)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($con, $sql, $lot);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($con);
                header("Location: lot.php?id=" . $lot_id);
            } else {
                print(mysqli_error($con));
            }
        }
    }
} else {
    $pageContent = include_template('403.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'title' => $title,
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
