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
        // Чтение файла в массив, где каждая строка файла преобразуется в массив данных с помощью функции str_getcsv.
        $data = array_map('str_getcsv', file($this->filePath));

        // Фильтрация массива $data. Удаляются строки, где имя пользователя (первый элемент массива) совпадает с $username.
        $newData = array_filter($data, function ($row) use ($username) {
            return $row[0] !== $username;
        });

        // Открытие файла для записи. Если файл существует, его содержимое уничтожается.
        $file = fopen($this->filePath, 'w');
        foreach ($newData as $line) {
            fputcsv($file, $line); // Запись каждой строки из $newData в файл.
        }
        fclose($file); // Закрытие файла после завершения записи.
    }
}
