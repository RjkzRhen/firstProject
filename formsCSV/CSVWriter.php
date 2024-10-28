<?php
namespace formsCSV;

use Exception;

class CSVWriter {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function addRecord(array $data): void {
        $handle = fopen($this->filePath, 'a');
        if (!$handle) {
            throw new Exception("Cannot open file: " . $this->filePath);
        }

        $csvLine = implode(';', $data);
        fwrite($handle, mb_convert_encoding($csvLine . "\n", 'UTF-8', 'UTF-8')); // Используем UTF-8 для записи
        fclose($handle);
    }
}