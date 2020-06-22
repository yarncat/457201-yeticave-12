<?php
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
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
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

function is_date_valid(string $date) : bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    if($dateTimeObj !== false && array_sum(date_get_last_errors()) === 0) {
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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lot = $_POST;
    $required = ['lot_name', 'category', 'message', 'lot_rate', 'lot_step', 'lot_date'];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
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

    if (mb_strlen($_POST['lot_name']) > 100) {
        $errors['lot_name'] = 'Внимание: максимальное количество символов в названии лота: 100';
    }

    if (mb_strlen($_POST['message']) > 255) {
        $errors['message'] = 'Внимание: максимальное количество символов в описании лота: 255';
    }

    if ($_POST['lot_rate'] && $_POST['lot_rate'] < 1) {
        $errors['lot_rate'] = 'Внимание: цена должна быть числом больше 0';
    } elseif ($_POST['lot_rate'] && !preg_match("/^[0-9 ]+$/", $_POST['lot_rate'])) {
        $errors['lot_rate'] = 'Введите, пожалуйста, корректное целое число';
    }

    if ($_POST['lot_step'] && !ctype_digit($_POST['lot_step'])) {
        $errors['lot_step'] = 'Внимание: ставка должна быть целым числом больше 0';
    }

    if ($_POST['lot_date'] && !is_date_valid($_POST['lot_date'])) {
        $errors['lot_date'] = 'Указанная дата должна быть больше текущей минимум на 1 сутки<br />Введите, пожалуйста, дату в указанном формате : ГГГГ-ММ-ДД';
    }

    if (count($errors)) {
        $pageContent = include_template('addlot.php', [
            'errors' => $errors,
            'categories' => $categories]);
    } else {
        $sql = 'INSERT INTO lots (lot_name, cat_code, lot_info, start_price, step_rate, final_date, author, image_link)
                     VALUES (?, ?, ?, ?, ?, ?, 1, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, $lot);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?id=" . $lot_id);

        } else {
            print(mysqli_error($con));
        }
    }

} else {
    $pageContent = include_template('addlot.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $pageContent,
    'categories' => $categories
]);

print($layout_content);
