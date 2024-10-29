<?php
namespace forms;

use PageInterface;

class PhoneForm extends AbstractForm implements PageInterface {
    // Метод для получения шаблона полей формы
    protected function getTemplate(): array {
        return [
            ['id' => 'full_name', 'name' => 'full_name', 'label' => 'ФИО', 'type' => 'select', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'phone_number', 'name' => 'phone_number', 'label' => 'Номер телефона', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true]
        ]; // Возвращаем массив с полями формы
    }
    // Метод для генерации HTML-кода формы
    public function getHtml(): string {
        $users = $this->getUsers(); // Получаем список пользователей из базы данных
        $html = '<!DOCTYPE html><html lang="ru"><head>
        <meta charset="UTF-8">
        <title>Добавление телефона</title>
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
    </head><body>
        <form action="/phone_form" method="post" id="phoneForm">';

        // Генерация HTML-кода для каждого поля формы
        foreach ($this->fields as $field) {
            $class = $field['isValid'] ? "req" : "error"; // Определяем класс для поля в зависимости от валидности
            $html .= '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>'; // Добавляем метку для поля

            if ($field['type'] === 'select') {
                $html .= '<select id="' . $field['id'] . '" name="' . $field['name'] . '" class="' . $class . '">'; // Открываем тег select
                foreach ($users as $user) {
                    $html .= '<option value="' . $user['id'] . '">' . $user['full_name'] . '</option>'; // Добавляем опции с пользователями
                }
                $html .= '</select><br>'; // Закрываем тег select
            } else {
                $html .= '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'"><br>'; // Добавляем поле ввода
            }
        }
        $html .= '<input type="submit" name="submit" value="Добавить телефон" id="button">'; // Добавляем кнопку отправки формы
        $html .= '</form></body></html>';

        return $html; // Возвращаем сгенерированный HTML-код формы
    }
    // Метод для получения списка пользователей из базы данных
    protected function getUsers(): array {
        $sql = "SELECT id, CONCAT(last_name, ' ', first_name, ' ', middle_name) AS full_name FROM name"; // SQL-запрос для получения пользователей
        $result = $this->db->conn->query($sql); // Выполняем запрос
        $users = []; // Инициализируем массив для пользователей

        if ($result->num_rows > 0) { // Проверяем, есть ли результаты
            while ($row = $result->fetch_assoc()) { // Перебираем результаты
                $users[] = $row; // Добавляем пользователя в массив
            }
        }

        return $users; // Возвращаем массив пользователей
    }
    // Метод для вставки данных в таблицу phone
    protected function insertIntoTable(array $dataTemplate, $con): void {
        $user_id = $dataTemplate[0]['value']; // Получаем значение поля "ФИО" (user_id) из формы
        $phone_number = $dataTemplate[1]['value']; // Получаем значение поля "Номер телефона" из формы
        // Подготовка SQL-запроса для вставки данных
        $sql = "INSERT INTO phone (user_id, value) VALUES (?, ?)"; // SQL-запрос для вставки данных
        $params = array('types' => 'is', 'values' => array($user_id, $phone_number)); // Подготовка параметров для запроса
        $stmt = $con->prepare($sql); // Подготовка SQL-запроса
        if ($params) {
            $stmt->bind_param($params['types'], ...$params['values']); // Привязка параметров к запросу
        }
        if (!$stmt->execute()) { // Выполнение SQL-запроса
            echo "Ошибка: " . $stmt->error; // Вывод ошибки, если запрос не выполнен
        } else {
            header("Location: /phone"); // Перенаправление на страницу с таблицей phone
            exit; // Завершение скрипта
        }
    }
}