<?php

function getTemplate(): array
{
    return [
        ['id' => 'last_name', 'name' => 'last_name', 'label' => 'Фамилия', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
        ['id' => 'first_name', 'name' => 'first_name', 'label' => 'Имя', 'type' => 'text', 'value' =>  '', 'required' => true, 'isValid' => true],
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

function isAllValid(array $dataTemplate): bool
{ foreach ($dataTemplate as $field) {
    if (!$field['isValid']) {
        return false;
    }
}
    return true;
}

function insertIntoTable(array $dataTemplate): void
{

}