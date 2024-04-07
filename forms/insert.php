<?php
include_once '../db/db.php';

if (isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "users1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $age = $_POST['age'];

    $sql = "INSERT INTO `name` (last_name, first_name, middle_name, age) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $last_name, $first_name, $middle_name, $age);
    $stmt->execute();

    header("Location: ../data/index.php");
    exit;

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавление пользователя</title>
</head>
<body>
<form action="insert.php" method="post">
    <label for="last_name">Фамилия:</label>
    <input type="text" id="last_name" name="last_name" required><br>
    <label for="first_name">Имя:</label>
    <input type="text" id="first_name" name="first_name" required><br>
    <label for="middle_name">Отчество:</label>
    <input type="text" id="middle_name" name="middle_name" required><br>
    <label for="age">Возраст:</label>
    <input type="number" id="age" name="age" required><br>
    <input type="submit" name="submit" value="Добавить пользователя">
</form>
</body>
</html>
