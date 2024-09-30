<?php
namespace forms;

use db\Database;

abstract class AbstractForm {
    protected array $fields; // Объявление свойства для хранения полей формы
    protected Database $db; // Объявление свойства для хранения объекта Database

    public function __construct(Database $db) {
        $this->db = $db; // Присваивание переданного объекта Database свойству $db
        $this->fields = $this->handleRequest(); // Обработка запроса и получение полей формы
        if ($this->isAllValid($this->fields)) { // Проверка валидности всех полей
            $this->insertIntoTable($this->fields, $this->db->conn); // Вставка данных в таблицу, если все поля валидны
        }
    }

    abstract protected function getTemplate(): array; // Абстрактный метод для получения шаблона полей формы

    protected function handleRequest(): array {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверка, была ли отправлена форма
            $fields = $this->getDataFromFormAndUpdateTemplate(); // Извлекает и обновляет данные формы
        } else {
            $fields = $this->getTemplate(); // Получает шаблон формы с пустыми значениями, если форма не отправлена
        }
        return $fields; // Возвращает поля формы
    }

    protected function getDataFromFormAndUpdateTemplate(): array {
        $fields = $this->getTemplate(); // Получение шаблона полей формы
        $result = [];
        foreach ($fields as $field) { // Перебор полей формы
            $field['value'] = $_POST[$field['name']] ?? ''; // Установка значения поля из POST-запроса или пустой строки
            $field['isValid'] = !empty($field['value']); // Установка флага валидности в true, если поле не пустое
            $result[] = $field; // Добавление поля в результирующий массив
        }
        return $result; // Возвращение обновленного шаблона полей формы
    }

    protected function isAllValid(array $dataTemplate): bool {
        foreach ($dataTemplate as $field) { // Перебор всех полей в шаблоне данных
            if ($field['required'] && empty($field['value'])) { // Проверка, является ли поле обязательным и пустым
                return false; // Если поле невалидно, возвращаем false
            }
        }
        return true; // Если все поля валидны, возвращаем true
    }

    abstract public function getHtml(): string; // Абстрактный метод для получения HTML-кода формы

    protected function insertIntoTable(array $dataTemplate, $con): void {
        if ($this instanceof \formsCSV\AddRecord) {
            // Подготовка данных для записи в CSV.
            $data = array_map(function ($field) {
                return $field['value'];
            }, $dataTemplate);

            // Попытка записи в CSV файл.
            try {
                $csvWriter = new \formsCSV\CSVWriter('otherFiles/OpenDocument.csv');
                $csvWriter->addRecord($data);
                // Перенаправление на страницу CSV-таблицы.
                header("Location: /csv");
                exit;
            } catch (\Exception $e) { // Обработка возможных исключений.
                echo "Error: " . $e->getMessage();
            }
        } else {
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
    }
}