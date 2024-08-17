<?php
namespace data;

use Exception;
use PageInterface;

abstract class CSVTable implements PageInterface
{
    protected $data;
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadData($this->filePath);
    }

    public function loadData($filePath): void
    {
        $handle = fopen($filePath, "r");
        if ($handle === false) {
            throw new Exception("Ошибка при открытии файла: " . $filePath);
        }
        $this->data = [];
        while (($line = fgetcsv($handle, 1000, ";")) !== false) {
            $line = array_map(function($value) {
                return mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
            }, $line);
            $this->data[] = $line;
        }
        fclose($handle);
    }

    abstract public function render(): string;
    public function readCsv(): array
    {
        $rows = [];
        foreach ($this->data as $line) {
            if (!is_array($line)) {
                $rows[] = str_getcsv($line, ';');
            } else {
                $rows[] = $line;
            }
        }
        return $rows;
    }

    public function getHtml(): string
    {
        $data = $this->readCsv();
        $html = "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<title>CSV Table</title>\n";
        $html .= $this->getStyle();
        $html .= "</head>\n<body>\n<table>\n<tr><th>Username</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Age</th><th>Action</th></tr>\n";
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