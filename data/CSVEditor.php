<?php
namespace data; // Определяет пространство имен для класса, что помогает избежать конфликта имен с другими классами в других библиотеках.

class CSVEditor { // Определение класса CSVEditor.
    private $filePath; // Объявление приватного свойства $filePath для хранения пути к файлу CSV.

    public function __construct($filePath) { // Конструктор класса, который вызывается при создании нового объекта CSVEditor.
        $this->filePath = $filePath; // Присваивание значения параметра $filePath приватному свойству $filePath.
    }

    public function deleteByUsername($username): void // Метод для удаления строки в CSV файле по имени пользователя.
    {
        $data = array_map('str_get-csv', file($this->filePath)); // Чтение файла в массив, где каждая строка файла преобразуется в массив данных с помощью функции str_get-csv.
        $newData = array_filter($data, function ($row) use ($username) { // Фильтрация массива $data. Удаляются строки, где имя пользователя (первый элемент массива) совпадает с $username.
            return $row[0] !== $username; // Возвращение true для элементов, которые не должны быть удалены.
        });

        $file = fopen($this->filePath, ''); // Открытие файла для записи. Если файл существует, его содержимое уничтожается.
        foreach ($newData as $line) { // Перебор массива $newData.
            fputcsv($file, $line); // Запись каждой строки из $newData в файл.
        }
        fclose($file); // Закрытие файла после завершения записи.
    }

    public function addRowToCSV(array $row): void {
        $file = fopen($this->filePath, 'a'); // Открытие файла для добавления данных
        fputcsv($file, $row); // Добавление новой строки в файл CSV
        fclose($file); // Закрытие файла
    }
}
