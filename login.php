<?php

require_once 'init.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST;
    $required = ['email', 'password'];

    foreach ($required as $field) {
        if (empty($login[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    $email = mysqli_real_escape_string($con, $login['email']);
    $sql = "SELECT * FROM users WHERE user_email = '$email'";
    $res = mysqli_query($con, $sql);
    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) && $user) {
        if (password_verify($login['password'], $user['user_password'])) {
            session_start();
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $pageContent = include_template('login.php', [
            'errors' => $errors,
            'categories' => $categories]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $pageContent = include_template('login.php', ['categories' => $categories]);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'title' => 'Авторизация',
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
