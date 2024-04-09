<?php

include '../db/db.php';

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
</head>
<body>
<form action="" method="post">
    <input type="hidden" name="id" value="ID_записи_для_изменения">
    <label for="last_name">Фамилия:</label>
    <input type="text" id="last_name" name="last_name" value=""><br>
    <label for="first_name">Имя:</label>
    <input type="text" id="first_name" name="first_name" value=""><br>
    <label for="middle_name">Отчество:</label>
    <input type="text" id="middle_name" name="middle_name" value=""><br>
    <label for="age">Возраст:</label>
    <input type="number" id="age" name="age" value=""><br>
    <input type="submit" name="update" value="Добавить"> <!-- Изменил значение кнопки на "Добавить" -->
</form>
</body>
</html>
