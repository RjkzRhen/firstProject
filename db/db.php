<?php

function getConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "users1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    return $conn;
}

function executeSQL($conn, $sql, $params = null) {
    $stmt = $conn->prepare($sql);

    if ($params) {
        $stmt->bind_param($params['types'], ...$params['values']);
    }

    if ($stmt->execute()) {
        return $stmt;
    } else {
        return false;
    }
}

function closeConnection($conn) {
    $conn->close();
}

function deleteRecord($id) {
    $conn = getConnection();
    $sql = "DELETE FROM `name` WHERE id = ?";
    $params = array('types' => 'i', 'values' => array($id));

    $stmt = executeSQL($conn, $sql, $params);

    if ($stmt) {
        echo "Запись успешно удалена.";
    } else {
        echo "Ошибка: " . $conn->error;
    }

    closeConnection($conn);
}

function insertRecord($data) {
    $conn = getConnection();
    $sql = "INSERT INTO `name` (last_name, first_name, middle_name, age) VALUES (?, ?, ?, ?)";
    $params = array('types' => 'sssi', 'values' => array($data['last_name'], $data['first_name'], $data['middle_name'], $data['age']));

    $stmt = executeSQL($conn, $sql, $params);

    if ($stmt) {
        echo "Новая запись успешно добавлена.";
    } else {
        echo "Ошибка при добавлении записи: " . $conn->error;
    }

    closeConnection($conn);
}

function getTableRows($minAge = 0) {
    $conn = getConnection();
    $sql = "SELECT * FROM `name` WHERE age >= ?";
    $params = array('types' => 'i', 'values' => array($minAge));

    $stmt = executeSQL($conn, $sql, $params);

    $rows = array();

    if ($stmt) {
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $ageClass = $row['age'] > 50 ? 'age-over-50' : '';
                $rows[] = array(
                    'id' => $row['id'],
                    'last_name' => $row['last_name'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'age' => $row['age'],
                    'ageClass' => $ageClass
                );
            }
        }
    }

    closeConnection($conn);
    return $rows;
}

?>
