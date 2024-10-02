<?php
namespace forms;

use db\Database;
use PageInterface;

class Form extends AbstractForm implements PageInterface {
protected function getTemplate(): array {
return [
['id' => 'last_name', 'name' => 'last_name', 'label' => 'Фамилия', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
['id' => 'first_name', 'name' => 'first_name', 'label' => 'Имя', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
['id' => 'middle_name', 'name' => 'middle_name', 'label' => 'Отчество', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
['id' => 'age', 'name' => 'age', 'label' => 'Возраст', 'type' => 'number', 'value' => '', 'required' => true, 'isValid' => true]
]; // Возвращение шаблона полей формы
}

public function getHtml(): string {
$html = '<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8">
    <title>Добавление пользователя</title>
    <style type="text/css">
        .error { border: 2px solid #ff0000; }
        .req:valid { border: 2px solid #000000; }
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); width: 400px; }
        label { font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        input.req { outline: none; }
        input[type="submit"] { background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
<form action="/form" method="post" id="userForm">';
    foreach ($this->fields as $field) { // Перебор всех полей формы
    $class = $field['isValid'] ? "req" : "error"; // Определение класса для поля в зависимости от валидности
    $html .= '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>'; // Добавление метки для поля
    $html .= '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'"><br>'; // Добавление поля ввода
    }
    $html .= '<input type="submit" name="submit" value="Добавить пользователя" id="button">'; // Добавление кнопки отправки формы
    $html .= '</form></body></html>';
return $html; // Возвращение сгенерированного HTML
}
}