<?php
namespace data; // ���������� ������������ ���� ��� ������, ��� �������� �������� ��������� ���� � ������� �������� � ������ �����������.

class CSVEditor { // ����������� ������ CSVEditor.
    private $filePath; // ���������� ���������� �������� $filePath ��� �������� ���� � ����� CSV.

    public function __construct($filePath) { // ����������� ������, ������� ���������� ��� �������� ������ ������� CSVEditor.
        $this->filePath = $filePath; // ������������ �������� ��������� $filePath ���������� �������� $filePath.
    }

    public function deleteByUsername($username): void // ����� ��� �������� ������ � CSV ����� �� ����� ������������.
    {
        $data = array_map('str_get-csv', file($this->filePath)); // ������ ����� � ������, ��� ������ ������ ����� ������������� � ������ ������ � ������� ������� str_get-csv.
        $newData = array_filter($data, function ($row) use ($username) { // ���������� ������� $data. ��������� ������, ��� ��� ������������ (������ ������� �������) ��������� � $username.
            return $row[0] !== $username; // ����������� true ��� ���������, ������� �� ������ ���� �������.
        });

        $file = fopen($this->filePath, 'w'); // �������� ����� ��� ������. ���� ���� ����������, ��� ���������� ������������.
        foreach ($newData as $line) { // ������� ������� $newData.
            fputcsv($file, $line); // ������ ������ ������ �� $newData � ����.
        }
        fclose($file); // �������� ����� ����� ���������� ������.
    }
}
