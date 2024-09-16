<?php
namespace formsCSV; // ���������� ������������ ���� ��� ������ CSVWriter.

class CSVWriter { // ���������� ������ CSVWriter.
    private $filePath; // ��������� �������� ��� �������� ���� � ����� CSV.

    public function __construct($filePath) { // ����������� ������, ��������� ���� � ����� ��� ��������.
        $this->filePath = $filePath; // ������������ �������� ��������� filePath �������� ������.
    }

    public function addRecord(array $data): void { // ����� ��� ���������� ������ � CSV ����, ��������� ������ ������.���� ����� ����������, ����� ��� ���� �����, ���������� �� ������� POST, ��������� � �������� �����������.
        $handle = fopen($this->filePath, 'a');  // ��������� ���� � ������ ����������.
        if (!$handle) { // ��������� ���������� �������� �����.
            throw new Exception("Cannot open file: " . $this->filePath); // ����������� ����������, ���� ���� �� ������ �������.
        }

        // ����������� ������� � ������, ���������� �������� ';'
        $csvLine = implode(';', $data);

        // ������ ������ � ���� � ��������������� ��������� � Windows-1251 �� UTF-8 � ����������� ������� ����� ������.
        fwrite($handle, mb_convert_encoding($csvLine . "\n", 'Windows-1251', 'UTF-8'));
        fclose($handle); // ��������� ����.
    }
}


