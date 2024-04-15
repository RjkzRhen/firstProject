<?php
include_once '../db/db.php';
include_once '../forms/insertVlada.php';
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
<form action="form.php" method="post" id="userForm">
    <?php
    if (isset($_POST['submit'])) {
        $fields = getDataFromFormAndUpdateTemplate();
        if (isAllValid($fields)) {
            $con = getConnection();
            insertIntoTable($fields, $con);
        }
    } else {
        $fields = getTemplate();
    }
    if (empty($errors)) {
    $sql = "INSERT INTO `name` (last_name, first_name, middle_name, age) VALUES (?, ?, ?, ?)";
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $values['last_name'], $values['first_name'], $values['middle_name'], $values['age']);
    $stmt->execute();

    header("Location: ../data/index.php");
    exit;
    }
    foreach ($fields as $field) {
        $class = $field['isValid'] ? "req" : "error";
        echo '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>';
        echo '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'" ><br>';
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
