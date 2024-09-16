<?php
namespace formsCSV; // Определяет пространство имен для класса CSVWriter.

class CSVWriter { // Объявление класса CSVWriter.
    private $filePath; // Приватное свойство для хранения пути к файлу CSV.

    public function __construct($filePath) { // Конструктор класса, принимает путь к файлу как параметр.
        $this->filePath = $filePath; // Присваивание значения параметра filePath свойству класса.
    }

    public function addRecord(array $data): void { // Метод для добавления записи в CSV файл, принимает массив данных.Этот метод вызывается, когда все поля формы, полученные из запроса POST, проверены и являются допустимыми.
        $handle = fopen($this->filePath, 'a');  // Открывает файл в режиме добавления.
        if (!$handle) { // Проверяет успешность открытия файла.
            throw new Exception("Cannot open file: " . $this->filePath); // Выбрасывает исключение, если файл не удаётся открыть.
        }

        // Конвертация массива в строку, разделённую символом ';'
        $csvLine = implode(';', $data);

        // Запись строки в файл с преобразованием кодировки в Windows-1251 из UTF-8 и добавлением символа новой строки.
        fwrite($handle, mb_convert_encoding($csvLine . "\n", 'Windows-1251', 'UTF-8'));
        fclose($handle); // Закрывает файл.
    }
}


