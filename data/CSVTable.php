<?php
namespace data; // Определяет пространство имен для класса CSVTable
use Exception; // Импортирует класс Exception для обработки исключений
use PageInterface;
use TableStyles;

class CSVTable implements PageInterface { // Определение класса CSVTable, который реализует интерфейс PageInterface
    private $filePath; // Приватное свойство для хранения пути к файлу CSV
    private array|false $csvData; // Приватное свойство для хранения данных из CSV файла или false в случае ошибки

    private \data\TableStyles $styles;

    /**
     * @throws Exception
     */
    public function __construct($filePath, \data\TableStyles $styles) {
        $this->filePath = $filePath;
        $this->styles = $styles;
        $this->csvData = file($this->filePath);

        if ($this->csvData === false) {
            throw new Exception("Error opening file: " . $filePath);
        }
        array_walk($this->csvData, function (&$line) {
            $detectedEncoding = mb_detect_encoding($line, ['Windows-1251', 'ISO-8859-1', 'UTF-8'], true);
            if ($detectedEncoding !== 'UTF-8') {
                $line = mb_convert_encoding($line, 'UTF-8', $detectedEncoding);
            }
            $line = str_getcsv($line, ';'); // Convert CSV string to array after encoding adjustment
        });
        echo '<pre>';
        print_r($this->csvData);
        echo '</pre>';

    }


    public function readCsv(): array {
        $rows = [];
        foreach ($this->csvData as $line) {
            // Convert each line from a CSV string to an array
            if (is_string($line)) {
                $rows[] = str_getcsv($line, ";");
            }
        }
        return $rows;
    }


    public function getHtml(): string {
        $data = $this->readCsv(); // Загрузка данных
        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang='en'>\n";
        $html .= "<head>\n";
        $html .= "<meta charset='UTF-8'>\n";
        $html .= "<title>CSV Table</title>\n";
        $html .= $this->styles->getStyles(); // Получение стилей
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= "<table>\n";
        $html .= "<tr>\n";
        foreach ($data[0] as $header) {
            $html .= "<th>" . htmlspecialchars($header) . "</th>\n"; // Добавление заголовков
        }
        $html .= "</tr>\n";
        for ($i = 1; $i < count($data); $i++) {
            if (count($data[$i]) == count($data[0])) { // Проверка на полноту данных
                $html .= "<tr>\n";
                foreach ($data[$i] as $cell) {
                    $html .= "<td>" . htmlspecialchars($cell) . "</td>\n"; // Добавление данных
                }
                $html .= "</tr>\n";
            }
        }
        $html .= "</table>\n";
        $html .= "</body>\n";
        $html .= "</html>";
        return $html;
    }
}