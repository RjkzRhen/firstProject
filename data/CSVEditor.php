<?php
namespace data;

class CSVEditor {
    public $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function deleteRow(): void
    {
        if (isset($_GET['delete_index'])) {
            $indexToDelete = $_GET['delete_index'];
            $data = file($this->filePath);
            if ($data !== false) {
                unset($data[$indexToDelete]); // �������� ������
                file_put_contents($this->filePath, implode("", $data)); // ������ ������ ������� � ����
                header("Location: '/csv'"); // �������� ������� � �������� � �������� CSV
            } else {
                echo "������ ��� ������ �����.";
            }
        }
    }
}