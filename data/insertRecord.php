<?php

include_once '../db/db.php';

if (isset($_POST['update'])) {
    $data = array(
        'id' => $_POST['id'],
        'last_name' => $_POST['last_name'],
        'first_name' => $_POST['first_name'],
        'middle_name' => $_POST['middle_name'],
        'age' => $_POST['age']

    );

    insertRecord($data);
    header("Location: index.php");
    exit;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Таблица пользователей</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input:invalid {
            border: 1px solid red;
        }
    </style>
</head>
<body>
<form action="" method="post">
    <input type="hidden" name="id" value="ID_записи_для_изменения">
    <label for="last_name">Фамилия:</label>
    <input type="text" id="last_name" name="last_name" value="" required><br>
    <label for="first_name">Имя:</label>
    <input type="text" id="first_name" name="first_name" value="" required><br>
    <label for="middle_name">Отчество:</label>
    <input type="text" id="middle_name" name="middle_name" value="" required><br>
    <label for="age">Возраст:</label>
    <input type="number" id="age" name="age" value="" required><br>
    <input type="submit" name="update" value="Обновить">
</form>
</body>
</html>

