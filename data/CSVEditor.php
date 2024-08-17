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
                unset($data[$indexToDelete]); // Удаление строки
                file_put_contents($this->filePath, implode("", $data)); // Запись данных обратно в файл
                header("Location: '/csv'"); // Редирект обратно к странице с таблицей CSV
            } else {
                echo "Ошибка при чтении файла.";
            }
        }
    }
}