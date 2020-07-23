<?php

require_once 'init.php';

$title = 'Регистрация';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $signup = $_POST;
    $required = ['email', 'name', 'password', 'contacts'];

    foreach ($required as $field) {
        if (empty($signup[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if ($signup['email'] && !filter_var($signup['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email';
    } else {
        $email = mysqli_real_escape_string($connect, $signup['email']);

        $sqlUserEmail = "SELECT id
                           FROM users
                          WHERE user_email = '$email'";

        $result = mysqli_query($connect, $sqlUserEmail);
        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if ($signup['password'] && (strlen($signup['password']) > 30 || strlen($signup['password']) < 6)) {
        $errors['password'] = 'Пароль должен быть длиной от 6 до 30 символов';
    } elseif ($signup['password'] && !preg_match("/^[a-zA-Z0-9]+$/", $signup['password'])) {
        $errors['password'] = 'Пароль должен содержать только латинские буквы и цифры';
    } else {
            $signup['password'] = password_hash($signup['password'], PASSWORD_DEFAULT);
    }

    if (mb_strlen($signup['name']) > 100) {
        $errors['lot_name'] = 'Внимание: максимальное количество символов в названии лота: 100';
    }

    if (mb_strlen($signup['contacts']) > 255) {
        $errors['contacts'] = 'Внимание: максимальное количество символов в описании лота: 255';
    }

    if (count($errors)) {
        $pageContent = include_template('signup.php', ['menu' => $menu, 'errors' => $errors]);
    } else {
        $sqlNewUser = 'INSERT INTO users (user_email, user_password, user_name, user_contacts)
                       VALUES (?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($connect, $sqlNewUser, $signup);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: login.php");
        } else {
            print(mysqli_error($connect));
        }
    }
} else {
    $pageContent = include_template('signup.php', ['menu' => $menu]);
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
