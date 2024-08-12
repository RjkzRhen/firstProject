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
            $rows[] = str_getcsv($line, ';');
        }
        array_shift($rows); // Удаление первой строки, содержащей заголовки

        return $rows; // Возвращение списка строк как результат работы метода
    }



    public function getHtml(): string {
        $data = $this->readCsv();
        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang='en'>\n";
        $html .= "<head>\n";
        $html .= "<meta charset='UTF-8'>\n";
        $html .= "<title>CSV Table</title>\n";
        $html .= $this->getStyle();
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= "<table>\n";
        $html .= "<tr><th>Username</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Age</th></tr>\n";
        foreach ($data as $index => $row) {
            $html .= "<tr>\n";
            foreach ($row as $key => $cell) {
                if ($key === 4 && $cell > 50) { // Используйте числовой индекс, если заголовки отсутствуют
                    $html .= "<td style='color: red;'>" . htmlspecialchars($cell) . "</td>\n";
                } else {
                    $html .= "<td>" . htmlspecialchars($cell) . "</td>\n";
                }
            }
            $html .= "<td><a href='delete.php?id={$index}'>Удалить</a></td>\n";
            $html .= "</tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</body>\n";
        $html .= "</html>";
        return $html;
    }

    private function getStyle(): string {
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
    </style>";
    }

}
