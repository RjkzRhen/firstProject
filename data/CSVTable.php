<?php
namespace data;


use Exception;
use PageInterface;
use data\CSVEditor;


abstract class AbstractTable {
    protected $data;

    /**
     * @throws Exception
     */


    abstract public function parseData();

}

interface Renderable {
    public function render();
}

 abstract class CSVTable extends AbstractTable implements Renderable, PageInterface
{
    private $filePath;
     private \data\CSVEditor $csvEditor;


     /**
     * @throws Exception
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadData($this->filePath);
        $this->csvEditor = new CSVEditor($this->filePath);

    }

    /**
     * @throws Exception
     */
    public function loadData($filePath): void
    {
        $this->data = file($filePath);
        if ($this->data === false) {
            throw new Exception("Ошибка при открытии файла: " . $filePath);
        }
        $this->parseData();
    }

    public function parseData(): void
    {
        array_walk($this->data, function (&$line) {
            $detectedEncoding = mb_detect_encoding($line, ['Windows-1251', 'ISO-8859-1', 'UTF-8'], true);
            $line = mb_convert_encoding($line, 'UTF-8', $detectedEncoding);
        });
    }

    public function render(): string
    {
        $html = "<table>\n";
        foreach ($this->data as $line) {
            $html .= "<tr>\n";
            foreach (str_getcsv($line, ';') as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>\n";
            }
            $html .= "</tr>\n";
        }
        $html .= "</table>\n";
        return $html;
    }

    public function readCsv(): array
    {
        $rows = [];
        foreach ($this->data as $line) {
            $rows[] = str_getcsv($line, ';');
        }
        return $rows;
    }

    /**
     * @throws Exception
     */
    public function getHtml(): string
    {
        $data = $this->readCsv();
        $html = "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<title>CSV Table</title>\n";
        $html .= $this->getStyle();
        $html .= "</head>\n<body>\n<table>\n<tr><th>Username</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Age</th><th>Удалить</th></tr>\n";
        foreach ($data as $index => $row) {
            $html .= "<tr>\n";
            foreach ($row as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>\n";
            }
            $html .= "<td><a href='CSVEditor.php?delete_index={$index}'>Delete</a></td>\n</tr>\n";
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
    </style>";
    }
}