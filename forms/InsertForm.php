<?php
namespace forms;

use config\Config;
use data\CSVEditor;
use db\Database;

$config = new Config('config.ini'); // Создание объекта конфигурации
$db = new Database($config); // Создание объекта базы данных с использованием конфигурации

/**
 * @method getDataFromFormAndUpdateTableTemplate()
 */
class InsertForm {

    public function getTemplate(): array {
        return [
            ['id' => 'last_name', 'name' => 'last_name', 'label' => 'Фамилия', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'first_name', 'name' => 'first_name', 'label' => 'Имя', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'middle_name', 'name' => 'middle_name', 'label' => 'Отчество', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'age', 'name' => 'age', 'label' => 'Возраст', 'type' => 'number', 'value' => '', 'required' => true, 'isValid' => true]
        ]; // Возвращение шаблона полей формы
    }

    public function getDataFromFormAndUpdateTemplate(): array {
        $fields = $this->getTemplate(); // Получение шаблона полей формы
        $result = [];
        foreach ($fields as $field) { // Перебор полей формы
            if (empty($_POST[$field['name']])) { // Проверка, пустое ли поле в POST-запросе
                $field['value'] = ''; // Установка значения поля в пустую строку
                $field['isValid'] = false; // Установка флага валидности в false
            } else {
                $field['value'] = $_POST[$field['name']]; // Установка значения поля из POST-запроса
                $field['isValid'] = true; // Установка флага валидности в true
            }
            $result[] = $field; // Добавление поля в результирующий массив
        }
        return $result; // Возвращение обновленного шаблона полей формы
    }

    public function insertIntoTable(array $dataTemplate, $con): void {
        $columns = implode(", ", array_map(function($item) {
            return "`" . $item['name'] . "`";
        }, $dataTemplate)); // Формирование строки с именами столбцов

        $values = implode(", ", array_map(function($item) use ($con) {
            return "'" . $con->real_escape_string($item['value']) . "'";
        }, $dataTemplate)); // Формирование строки со значениями, экранированными для безопасности

        $sql = "INSERT INTO `name` ($columns) VALUES ($values)"; // Формирование SQL-запроса для вставки данных

        if ($con->query($sql)) { // Выполнение SQL-запроса
            header("Location: /table"); // Перенаправление на страницу таблицы
            exit; // Завершение скрипта
        } else {
            echo "Ошибка: " . $sql . "<br>" . $con->error; // Вывод ошибки, если запрос не выполнен
        }
    }

    /**
     * @throws Exception
     */
    public function handleRequest(): array {
        if (isset($_POST['submit'])) { // Проверка, была ли отправлена форма
            $fields = $this->getDataFromFormAndUpdateTemplate(); // Извлекает и обновляет данные формы

        } else {
            $fields = $this->getTemplate(); // Получает шаблон формы с пустыми значениями, если форма не отправлена
        }
        return $fields; // Возвращает поля формы
    }
}
/*
 * handleRequest
 */