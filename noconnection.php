<?php

require_once 'helpers.php';
require_once 'functions.php';

$title = 'Нет соединения с базой данных';

$menu = "
  <nav class='nav'>
    <ul class='nav__list container'>
      <li class='nav__item'>
        <a>Цирк уехал, клоуны разбежались</a>
      </li>
      <li class='nav__item'>
        <a>Наташ, просыпайся, мы там всё уронили</a>
      </li>
      <li class='nav__item'>
        <a>Аукцион признан недействительным</a>
      </li>
    </ul>
  </nav>
";

$pageContent = include_template('noconnection.php');

$layoutContent = include_template('layout.php', [
    'menu' => $menu,
    'title' => $title,
    'content' => $pageContent
]);

print($layoutContent);
