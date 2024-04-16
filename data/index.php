<?php
include_once '../db/db.php';

$minAge = isset($_GET['minAge']) ? (int)$_GET['minAge'] : 0;
$tableRows = getTableRows($minAge);


function displayTable($tableRows) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Возраст</th><th>Действия</th></tr>";
    foreach ($tableRows as $row) {
        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['last_name']."</td>";
        echo "<td>".$row['first_name']."</td>";
        echo "<td>".$row['middle_name']."</td>";
        $ageClass = $row['age'] > 50 ? 'age-over-50' : '';
        echo "<td class=\"". $ageClass ."\">".$row['age']."</td>";
        echo "<td><a href='delete.php?id=".$row['id']."'>Удалить</a></td>";
        echo "<td><a href='insertRecord.php?id=".$row['id']."'>Добавить еще</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}

displayTable($tableRows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Таблица пользователей</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #ffffff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .age-over-50 {
            color: red;
        }
    </style>
</head>
<body>
<form action="" method="get">
    <label for="minAge">Минимальный возраст:</label>
    <input type="number" id="minAge" name="minAge" value="<?= $minAge ?>">
    <input type="submit" value="Фильтровать">
</form>
</body>
</html>