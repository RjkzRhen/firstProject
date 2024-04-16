<?php
include_once '../db/db.php';
function getTemplate(): array
{
    return [
        ['id' => 'last_name', 'name' => 'last_name', 'label' => 'Фамилия', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
        ['id' => 'first_name', 'name' => 'first_name', 'label' => 'Имя', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
        ['id' => 'middle_name', 'name' => 'middle_name', 'label' => 'Отчество', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
        ['id' => 'age', 'name' => 'age', 'label' => 'Возраст', 'type' => 'number', 'value' => '', 'required' => true, 'isValid' => true]
    ];
}

function getDataFromFormAndUpdateTemplate(): array
{
    $fields = getTemplate();

    $result = [];
    foreach ($fields as $field) {
        if (empty($_POST[$field['name']])) {
            $field['value'] = '';
            $field['isValid'] = false;
        } else {
            $field['value'] = $_POST[$field['name']];
            $field['isValid'] = true;
        }

        $result[] = $field;
    }

    return $result;
}

function isAllValid(array $dataTemplate): bool {
    return array_reduce($dataTemplate, function($isValid, $field) {
        return $isValid && (!$field['required'] || !empty($field['value']));
    }, true);
}
function insertIntoTable(array $dataTemplate, $con): void
{
    $columns = implode(", ", array_map(function($item) {
        return "`" . $item['name'] . "`";
    }, $dataTemplate));

    $values = implode(", ", array_map(function($item) use ($con) {
        return "'" . $con->real_escape_string($item['value']) . "'";
    }, $dataTemplate));

    $sql = "INSERT INTO `name` ($columns) VALUES ($values)";

    if ($con->query($sql)) {

        header("Location: ../data/index.php");
        exit;
    } else {
        echo "Ошибка: " . $sql . "<br>" . $con->error;
    }
}