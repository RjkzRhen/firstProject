<?php
namespace data; // Определяет пространство имен для класса CSVTable
use Exception; // Импортирует класс Exception для обработки исключений
use PageInterface; // Использует интерфейс PageInterface для обеспечения совместимости класса CSVTable

class CSVTable implements PageInterface { // Определение класса CSVTable, который реализует интерфейс PageInterface
    private $filePath; // Приватное свойство для хранения пути к файлу CSV
    private array|false $csvData; // Приватное свойство для хранения данных из CSV файла или false в случае ошибки

    /**
     * @throws Exception
     */
    public function __construct($filePath) { // Конструктор класса, инициализирующий объект с путем к файлу CSV
        $this->filePath = $filePath; // Присваивание значения свойству filePath
        $this->csvData = file($this->filePath); // Загрузка файла CSV и присвоение данных свойству csvData
        if ($this->csvData === false) { // Проверка успешности загрузки файла
            throw new Exception("Ошибка при открытии" . $filePath); // Генерация исключения в случае ошибки
        }
        array_walk($this->csvData, function (&$line) {
            $detectedEncoding = mb_detect_encoding($line, array('Windows-1251', 'ISO-8859-1', 'UTF-8'), true);
            $line = mb_convert_encoding($line, 'UTF-8', $detectedEncoding);
        });

    }


    public function readCsv(): array { // Метод для чтения данных из CSV файла и возвращения их в виде массива
        $rows = []; // Инициализация пустого массива для хранения строк данных
        foreach ($this->csvData as $line) { // Цикл по строкам данных файла
            $rows[] = str_getcsv($line); // Преобразование строки в массив и добавление в список строк
        }
        return $rows; // Возвращение списка строк как результат работы метода
    }

    public function getHtml(): string { // Метод для генерации HTML кода таблицы на основе данных CSV
        $data = $this->readCsv(); // Получение данных из CSV файла методом readCsv
        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang='en'>\n";
        $html .= "<head>\n";
        $html .= "<meta charset='UTF-8'>\n";
        $html .= "<title>CSV Table</title>\n";
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= "<table border='1'>\n";
        foreach ($data as $row) {
            $html .= "<tr>\n";
            foreach ($row as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>\n";
            }
            $html .= "</tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</body>\n";
        $html .= "</html>";
        return $html; // Возврат сформированного HTML кода как результат работы метода
    }

}