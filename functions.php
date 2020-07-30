<?php

/**
 * Сохраняет введённые значения в полях формы, переданные методом POST
 *
 * @param $name Введённое значение в поле формы
 *
 * @return string Возвращаемое значение, если таковое есть в массиве $_POST
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Форматирует сумму(цену), разделяя разряды пробелом и добавляя знак валюты
 *
 * @param int $price Заданное для форматирования число
 * @param string $currencySign Знак валюты (соответствующий символ Юникода), опционально
 *
 * @return string Целое число с символом валюты, если таковой был указан.
 */
function formatSum($price, $currencySign = "")
{
    $roundSum = ceil($price);
    $result = number_format($roundSum, 0, ",", " ") . $currencySign;
    return $result;
}

/**
 * Рассчитывает оставшееся время до конца аукциона в часах и минутах
 *
 * @param string $findate Дата окончания аукциона
 *
 * @return array Массив, где первое значение - число часов, второе - число минут
 */
function getDateRange($findate)
{
    $endDate = strtotime($findate);
    $nowDate = strtotime('now');
    $diffDate = $endDate - $nowDate;
    $hours = intval($diffDate / 3600);
    $arr[] = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = intval(($diffDate % 3600) / 60);
    $arr[] = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    return $arr;
}

/**
 * Рассчитывает время, которое прошло с момента создания ставки
 *
 * @param string $date Дата создания ставки
 *
 * @return string Сколько времени назад была сделана ставка
 */
function getDifferenceTime($date)
{
    $dateNow = date("Y-m-d H:i:s");
    $today = date_create($dateNow);
    $dateRate = date_create($date);
    $days = $today->format('d') - $dateRate->format('d');
    $hours = $today->format('H') - $dateRate->format('H');
    $minutes = $today->format('i') - $dateRate->format('i');

    if ($days == 1) {
        return date_format($dateRate, "Вчера в H:i");
    } elseif ($days < 1) {
        if ($hours === 0) {
            if ($hours === 0 && $minutes === 0) {
                return 'Только что';
            }
            return $minutes . get_noun_plural_form($minutes, ' минуту', ' минуты', ' минут') . ' назад';
        } elseif ($hours > 0) {
            return $hours . get_noun_plural_form($hours, ' час', ' часа', ' часов') . ' назад';
        }
    }
    $date = date_create($date);
    return date_format($date, "d.m.y в H:i");
}

/**
 * Получает запрошенные данные из БД
 *
 * @param $connect Параметры подключения к базе данных
 * @param string $sqlQuery SQL-запрос
 *
 * @return Возвращает результаты запроса в виде вложенного (двумерного) ассоциативного массива
 */
function getResultAsArray($connect, $sqlQuery)
{
    $result = mysqli_query($connect, $sqlQuery);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получает запрошенные данные из БД
 *
 * @param $connect Параметры подключения к базе данных
 * @param string $sqlQuery SQL-запрос
 *
 * @return int Возвращает результаты запроса в виде числа - количества строк результатов запроса
 */
function getNumRows($connect, $sqlQuery)
{
    $result = mysqli_query($connect, $sqlQuery);
    return mysqli_num_rows($result);
}

/**
 * Выполняет подготовленный запрос
 * (для операторов INSERT, UPDATE)
 *
 * @param $connect Параметры подключения к базе данных
 * @param string $sqlQuery SQL-запрос
 * @param array $array Массив данных
 *
 * @return bool Возвращает true в случае успешного завершения, иначе false
 */
function getPrepareStmt($connect, $sqlQuery, $array)
{
    $stmt = db_get_prepare_stmt($connect, $sqlQuery, $array);
    return mysqli_stmt_execute($stmt);
}

/**
 * Выполняет подготовленный запрос и получает данные запроса
 * (для оператора SELECT)
 *
 * @param $connect Параметры подключения к базе данных
 * @param string $sqlQuery SQL-запрос
 * @param array $array Массив данных
 *
 * @return Возвращает результат успешно выполненного запроса, иначе false
 */
function getResultPrepareStmt($connect, $sqlQuery, $array)
{
    $stmt = db_get_prepare_stmt($connect, $sqlQuery, [$array]);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
