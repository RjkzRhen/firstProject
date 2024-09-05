<?php
namespace data;
require_once 'CSVEditor.php';
use Exception;
use PageInterface;

abstract class CSVTable implements PageInterface // Объявление абстрактного класса CSVTable, который должен реализовать методы из PageInterface
{
    /**
     * @var CSVEditor
     */
    private CSVEditor $csvEditor; // Приватное свойство для экземпляра класса CSVEditor
    protected $data; // Защищённое свойство для хранения данных из CSV файла
    private $filePath; // Приватное свойство для хранения пути к файлу CSV
    public function __construct($filePath) // Конструктор класса с параметром пути к файлу
    {
        $this->filePath = $filePath; // Инициализация свойства filePath значением переданного аргумента
        $this->loadData($this->filePath); // Вызов метода loadData для загрузки данных из файла
        $this->csvEditor = new CSVEditor($filePath); // Создание нового объекта CSVEditor для управления файлом CSV
    }

    public function loadData($filePath): void // Метод для загрузки данных из файла CSV
    {
        $handle = fopen($filePath, "r"); // Открытие файла в режиме чтения
        if ($handle === false) { // Проверка успешности открытия файла
            throw new Exception("Ошибка при открытии файла: " . $filePath); // Генерация исключения при ошибке открытия
        }
        $this->data = []; // Инициализация массива данных
        while (($line = fgetcsv($handle, 1000, ";")) !== false) { // Чтение строки из файла CSV
            $line = array_map(function($value) { // Преобразование каждого значения строки
                return mb_convert_encoding($value, 'UTF-8', 'Windows-1251'); // Конвертация кодировки значения в UTF-8
            }, $line);
            $this->data[] = $line; // Добавление обработанной строки в массив данных
        }
        fclose($handle); // Закрытие файла
    }

    abstract public function render(): string; // Абстрактный метод для генерации HTML представления, должен быть реализован в наследниках

    public function readCsv(): array // Метод для чтения данных из массива $data
    {
        $rows = []; // Инициализация массива для хранения строк данных
        foreach ($this->data as $line) { // Перебор всех строк данных
            if (!is_array($line)) { // Проверка, является ли строка массивом
                $rows[] = str_getcsv($line, ';'); // Преобразование строки в массив и добавление в $rows
            } else {
                $rows[] = $line; // Добавление строки в $rows как есть, если она уже является массивом
            }
        }
        return $rows; // Возвращение массива строк
    }

    public function getHtml(): string
    {
        $data = $this->readCsv();
        $html = "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<title>CSV Table</title>\n";
        $html .= $this->getStyle();
        $html .= "</head>\n<body>\n<table>\n<tr><th>Username</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Age</th><th>Action</th></tr>\n";

        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Assuming the first row might be headers
            $html .= "<tr>\n";
            foreach ($row as $cellIndex => $cell) {
                $style = '';
                if ($cellIndex == 4 && (int)$cell > 50) { // Check if the index corresponds to 'Age' and the value is greater than 50
                    $style = ' class="age-over-50"';
                }
                $html .= "<td" . $style . ">" . htmlspecialchars($cell) . "</td>\n";
            }
            $username = htmlspecialchars($row[0]);
            $html .= "<td><a href='index.php?delete_username={$username}'>Delete</a></td>\n</tr>\n";
        }
        $html .= "</table>\n</body>\n</html>";
        return $html;
    }

    private function getStyle(): string
    {
        return "<style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
         .age-over-50 {
        color: red;
        font-weight: bold;
    }
    </style>";
    }


}