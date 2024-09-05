<?php
namespace formsCSV;

class CSVWriter {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function addRecord(array $data): void {
        $handle = fopen($this->filePath, 'a');  // Open file for appending
        if (!$handle) {
            throw new Exception("Cannot open file: " . $this->filePath);
        }

        // Convert array to ';' delimited string
        $csvLine = implode(';', $data);

        // Write the line with correct encoding
        fwrite($handle, mb_convert_encoding($csvLine . "\n", 'Windows-1251', 'UTF-8'));
        fclose($handle);
    }
}


