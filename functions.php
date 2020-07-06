<?php

function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

function db_get_prepare_stmt($con, $sql, $data = [])
{
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($con);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($con) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($con);
            die($errorMsg);
        }
    }

    return $stmt;
}

function formatSum($price)
{
    $roundSum = ceil($price);
    $result = number_format($roundSum, 0, ",", " ");
    return $result;
}

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

function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    if ($dateTimeObj !== false && array_sum(date_get_last_errors()) === 0) {
        $endTime = (strtotime($date) - strtotime('now')) / 86400;
        if ($endTime < 1) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
