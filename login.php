<?php

require_once 'init.php';

$title = 'Авторизация';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST;
    $required = ['email', 'password'];

    foreach ($required as $field) {
        if (empty($login[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    $email = mysqli_real_escape_string($connect, $login['email']);

    $sqlUserEmail = "SELECT *
                       FROM users
                      WHERE user_email = '$email'";

    $result = mysqli_query($connect, $sqlUserEmail);
    $user = $result ? mysqli_fetch_assoc($result) : null;

    if (!count($errors) && $user) {
        if (password_verify($login['password'], $user['user_password'])) {
            session_start();
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } elseif (empty($login['email'])) {
        $errors['email'] = 'Поле не заполнено';
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $pageContent = include_template('login.php', ['menu' => $menu, 'errors' => $errors]);
    } else {
        header("Location: /");
        exit();
    }
} else {
    $pageContent = include_template('login.php', ['menu' => $menu]);
}

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'userName' => $userName,
    'content' => $pageContent
]);

print($layoutContent);
