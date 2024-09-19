<?php
namespace data;

class CSVEditor
{
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function deleteByUsername($username): void
    {
        // ������ ����� � ������, ��� ������ ������ ����� ������������� � ������ ������ � ������� ������� str_getcsv.
        $data = array_map('str_getcsv', file($this->filePath));

        // ���������� ������� $data. ��������� ������, ��� ��� ������������ (������ ������� �������) ��������� � $username.
        $newData = array_filter($data, function ($row) use ($username) {
            return $row[0] !== $username;
        });

        // �������� ����� ��� ������. ���� ���� ����������, ��� ���������� ������������.
        $file = fopen($this->filePath, 'w');
        foreach ($newData as $line) {
            fputcsv($file, $line); // ������ ������ ������ �� $newData � ����.
        }
        fclose($file); // �������� ����� ����� ���������� ������.
    }
}
