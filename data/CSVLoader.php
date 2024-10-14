<?php
namespace data;

use Exception;

// Класс для загрузки данных из CSV-файла
class CSVLoader {
    private $filePath; // Свойство для хранения пути к CSV-файлу

    // Конструктор класса
    public function __construct($filePath) {
        $this->filePath = $filePath; // Присваивание переданного пути к CSV-файлу свойству $filePath
    }

    // Метод для загрузки данных из CSV-файла
    public function loadData(): array {
        if (!file_exists($this->filePath)) { // Проверка существования файла
            throw new Exception("Файл не найден: " . $this->filePath); // Выброс исключения, если файл не найден
        }

        $handle = fopen($this->filePath, "r"); // Открытие CSV-файла для чтения
        if ($handle === false) {
            throw new Exception("Ошибка при открытии файла: " . $this->filePath); // Выброс исключения в случае ошибки
        }

        $data = [];
        while (($line = fgetcsv($handle, 1000, ";")) !== false) { // Чтение строк из CSV-файла
            $line = array_map([$this, 'convertEncoding'], $line); // Конвертация кодировки значений
            $data[] = $line; // Добавление строки в массив данных
        }
        fclose($handle); // Закрытие файла

        return $data; // Возвращение загруженных данных
    }
    // Метод для конвертации кодировки значений
    public function convertEncoding($value): array|false|string|null {
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
    }
    // Метод для получения пути к файлу
    public function getFilePath(): string {
        return $this->filePath;
    }
}