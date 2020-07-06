<?php

require_once 'functions.php';

$is_auth = rand(0, 1);

$user_name = 'Александр';

$con = mysqli_connect("localhost", "root", "_caberne55_S", "yeticave");
mysqli_set_charset($con, "utf8");

if ($con) {
    $sqlCat = 'SELECT * FROM Categories';
    if ($result = mysqli_query($con, $sqlCat)) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $signup = $_POST;
    $required = ['email', 'name', 'password', 'contacts'];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if ($_POST['email'] && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email';
    } else {
        $email = mysqli_real_escape_string($con, $signup['email']);
        $sql = "SELECT id FROM users WHERE user_email = '$email'";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if ($_POST['password'] && (strlen($_POST['password']) > 30 || strlen($_POST['password']) < 6)) {
        $errors['password'] = 'Пароль должен быть длиной от 6 до 30 символов';
    } elseif ($_POST['password'] && !preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
        $errors['password'] = 'Пароль должен содержать только латинские буквы и цифры';
    } else {
            $signup['password'] = password_hash($signup['password'], PASSWORD_DEFAULT);
    }

    if (mb_strlen($_POST['name']) > 100) {
        $errors['lot_name'] = 'Внимание: максимальное количество символов в названии лота: 100';
    }

    if (mb_strlen($_POST['contacts']) > 255) {
        $errors['contacts'] = 'Внимание: максимальное количество символов в описании лота: 255';
    }

    if (count($errors)) {
        $pageContent = include_template('sign-up.php', [
            'errors' => $errors,
            'categories' => $categories]);
    } else {
        $sql = 'INSERT INTO users (user_email, user_password, user_name, user_contacts)
                     VALUES (?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, $signup);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: login.php");
        } else {
            print(mysqli_error($con));
        }
    }
} else {
    $pageContent = include_template('sign-up.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'title' => 'Регистрация',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
