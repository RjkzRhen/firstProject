<?php
namespace forms;

use Database;
use InsertForm;

include_once __DIR__ . '/../db/Database.php';

require_once __DIR__ . '/../config/Config.php';
class Form {
    private InsertForm $insertForm;
    private Database $db;

    private array $fields;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->insertForm = new InsertForm();
        $fields = $this->insertForm->handleRequest();
        $this->fields = $fields;  // Сохраняем поля для использования в getHtml()
    }

   // public function getHtml(): string
   // {
   //     $fields = $this->insertForm->handleRequest(); // Отправляет запрос к insertForm для обработки формы и получает поля формыМ
    //      if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->insertForm->isAllValid($fields)) { // Проверяет все ли поля формы валидны в методе POST
    //          $this->insertForm->insertIntoTable($fields, $this->db->conn);// Если условия выполнены, вставляет данные формы в таблицу базы данных
    //      }
    //     return $this->render($fields); // ОТПРАВЛЯЕТ ДАННЫЕ НА HTML-страницу, используя данные формы;
    // }

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
        foreach ($this->fields as $field) {
            $class = $field['isValid'] ? "req" : "error";
            $html .= '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>';
            $html .= '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'"><br>';
        }
        $html .= '<input type="submit" name="submit" value="Добавить пользователя" id="button">';
        $html .= '</form></body></html>';
        return $html;
    }
}