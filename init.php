<?php

require_once 'helpers.php';
require_once 'functions.php';
session_start();

$connect = mysqli_connect("localhost", "root", "_caberne55_S", "yeticave");
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    header("Location: noconnection.php");
}

$userName = "";

if (isset($_SESSION['user'])) {
    $userName = $_SESSION['user']['user_name'];
}

$sqlCategories = 'SELECT * FROM Categories';

$categories = getResultAsArray($connect, $sqlCategories);

$menu = include_template('menu.php', ['categories' => $categories]);

$errors = [];
