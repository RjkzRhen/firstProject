<?php
namespace data;
use Exception;
use PageInterface;

class CSVTable extends AbstractTable implements PageInterface
{
    private CSVEditor $csvEditor;
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadData($this->filePath);
        $this->csvEditor = new CSVEditor($filePath);
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
        $html .= "</head>\n<body>\n<table>\n<tr><th>Username</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Age</th><th>Удалить</th></tr>\n";

        foreach ($data as $index => $row) {
            if ($index == 0) continue;
            $html .= "<tr>\n";
            foreach ($row as $cellIndex => $cell) {
                $style = '';
                if ($cellIndex == 4 && (int)$cell > 50) {
                    $style = ' class="age-over-50"';
                }
                $html .= "<td" . $style . ">" . htmlspecialchars($cell) . "</td>\n";
            }
            $username = htmlspecialchars($row[0]);
            $html .= "<td><a href='index.php?delete_username={$username}'>Удалить</a></td>\n</tr>\n";
        }
        $html .= "</table>\n</body>\n</html>";
        return $html;
    }

    public function deleteByUsername($username): void
    {
        $this->csvEditor->deleteByUsername($username);
        $this->loadData($this->filePath); // Перезагрузка данных после удаления
    }
}