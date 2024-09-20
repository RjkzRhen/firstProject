<?php
namespace data;

use Exception;
use PageInterface;

class CSVTable extends AbstractTable implements PageInterface {
    private CSVEditor $csvEditor; // Объявление свойства для хранения объекта CSVEditor
    private $filePath; // Объявление свойства для хранения пути к CSV-файлу

    public function __construct($filePath) {
        $this->filePath = $filePath; // Присваивание переданного пути к CSV-файлу свойству $filePath
        $this->loadData($this->filePath); // Загрузка данных из CSV-файла
        $this->csvEditor = new CSVEditor($filePath); // Создание объекта CSVEditor с использованием пути к CSV-файлу
    }

    public function loadData($filePath): void {
        $handle = fopen($filePath, "r"); // Открытие CSV-файла для чтения
        if ($handle === false) {
            throw new Exception("Ошибка при открытии файла: " . $filePath); // Выброс исключения в случае ошибки
        }
        $this->data = [];
        while (($line = fgetcsv($handle, 1000, ";")) !== false) { // Чтение строк из CSV-файла
            $line = array_map(function($value) {
                return mb_convert_encoding($value, 'UTF-8', 'Windows-1251'); // Конвертация кодировки значений
            }, $line);
            $this->data[] = $line; // Добавление строки в массив данных
        }
        fclose($handle); // Закрытие файла
    }

    public function getHtml(): string {
        $html = "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<title>CSV Table</title>\n";
        $html .= $this->getStyle(); // Получение стилей для таблицы
        $html .= "</head>\n<body>\n<table>\n<tr><th>Username</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Age</th><th>Удалить</th></tr>\n";

        foreach ($this->data as $index => $row) { // Перебор строк данных
            if ($index == 0) continue; // Пропуск первой строки (заголовков)
            $html .= "<tr>\n";
            foreach ($row as $cellIndex => $cell) { // Перебор ячеек строки
                $style = '';
                if ($cellIndex == 4 && (int)$cell > 50) { // Проверка возраста и добавление класса для стилизации
                    $style = ' class="age-over-50"';
                }
                $html .= "<td" . $style . ">" . htmlspecialchars($cell) . "</td>\n"; // Добавление ячейки в HTML
            }
            $username = htmlspecialchars($row[0]); // Получение имени пользователя
            $html .= "<td><a href='index.php?delete_username={$username}'>Удалить</a></td>\n</tr>\n"; // Добавление ссылки для удаления
        }
        $html .= "</table>\n</body>\n</html>";
        return $html; // Возвращение сгенерированного HTML-кода
    }

    public function deleteByUsername($username): void {
        $this->csvEditor->deleteByUsername($username); // Удаление записи по имени пользователя
        $this->loadData($this->filePath); // Перезагрузка данных после удаления
    }
}