<?php
include_once '../db/db.php';

$errors = [];
$values = [];

if (isset($_POST['submit'])) {
    $conn = getConnection();

    $fields = ['last_name' => 'Фамилия', 'first_name' => 'Имя', 'middle_name' => 'Отчество', 'age' => 'Возраст'];

    foreach ($fields as $key => $label) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Поле "' . $label . '" обязательно для заполнения';
        } else {
            $values[$key] = $_POST[$key];
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO `name` (last_name, first_name, middle_name, age) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $values['last_name'], $values['first_name'], $values['middle_name'], $values['age']);
        $stmt->execute();

        header("Location: ../data/index.php");
        exit;

        $stmt->close();
    } else {
        foreach ($errors as $key => $error) {
            echo "<script>document.getElementById('$key').classList.add('error');</script>";
        }
    }


    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавление пользователя</title>
    <style type="text/css">
        .error {
            border: 2px solid #ff0000;
        }
        .req:invalid {
            border: 2px solid #ff0000;
        }
        .req:valid {
            border: 2px solid #000000;
        }
    </style>
</head>
<body>
<form action="insert.php" method="post" id="userForm">
    <?php
    $fields = [
        ['id' => 'last_name', 'name' => 'last_name', 'label' => 'Фамилия', 'type' => 'text', 'value' => isset($values['last_name']) ? $values['last_name'] : '', 'required' => true],
        ['id' => 'first_name', 'name' => 'first_name', 'label' => 'Имя', 'type' => 'text', 'value' => isset($values['first_name']) ? $values['first_name'] : '', 'required' => true],
        ['id' => 'middle_name', 'name' => 'middle_name', 'label' => 'Отчество', 'type' => 'text', 'value' => isset($values['middle_name']) ? $values['middle_name'] : '', 'required' => true],
        ['id' => 'age', 'name' => 'age', 'label' => 'Возраст', 'type' => 'number', 'value' => isset($values['age']) ? $values['age'] : '', 'required' => true]
    ];

    foreach ($fields as $field) {
        echo '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>';
        echo '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '" class="req"><br>';
    }
    ?>
    <input type="submit" name="submit" value="Добавить пользователя" id="button">
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#button').on('click', function() {
            $('input.req').addClass('req');
        });
    });
</script>
</body>
</html>