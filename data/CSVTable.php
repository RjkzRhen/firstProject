<?php
namespace data; // Определяем пространство имен для класса

use Exception; // Импортируем класс Exception для обработки исключений
use PageInterface; // Импортируем интерфейс PageInterface

class CSVTable extends AbstractTable implements PageInterface // Определяем класс CSVTable, который наследует AbstractTable и реализует PageInterface
{
    private CSVEditor $csvEditor; // Свойство для хранения объекта CSVEditor
    private string $filePath; // Свойство для хранения пути к CSV-файлу

    public function __construct($filePath) // Конструктор класса, принимает путь к CSV-файлу
    {
        $this->filePath = $filePath; // Присваиваем путь к CSV-файлу свойству класса
        $this->csvEditor = new CSVEditor($filePath); // Создаем объект CSVEditor
        $this->minAge = isset($_GET['minAge']) ? (int)$_GET['minAge'] : 0; // Получаем минимальный возраст из GET-параметра или устанавливаем его в 0

        parent::__construct(); // Вызываем конструктор родительского класса
    }

    public function loadData(): void // Метод для загрузки данных из CSV-файла
    {
        if (!file_exists($this->filePath)) { // Проверяем существование файла
            throw new Exception("Файл не найден: " . $this->filePath); // Выбрасываем исключение, если файл не найден
        }

        $handle = fopen($this->filePath, 'rb'); // Открываем CSV-файл для чтения
        if ($handle === false) { // Проверяем ошибку открытия файла
            throw new Exception("Ошибка при открытии файла: " . $this->filePath); // Выбрасываем исключение в случае ошибки
        }

        $this->data = []; // Инициализируем массив для хранения данных
        $firstLine = true; // Флаг для пропуска первой строки
        while (($line = fgetcsv($handle, 1000, ";")) !== false) { // Читаем строки из CSV-файла
            if ($firstLine) { // Пропускаем первую строку
                $firstLine = false;
                continue;
            }
            $line = array_map([$this, 'convertEncoding'], $line); // Преобразуем кодировку строки
            $this->data[] = $line; // Добавляем строку в массив данных
        }
        fclose($handle); // Закрываем файл
    }

    protected function convertEncoding($value): array|false|string|null // Метод для преобразования кодировки значения
    {
        return mb_convert_encoding($value, 'UTF-8', 'auto'); // Возвращаем значение, преобразованное в UTF-8
    }

    public function deleteByUsername($username): void // Метод для удаления записи по имени пользователя
    {
        $this->csvEditor->deleteByUsername($username); // Удаляем запись по имени пользователя
        $this->loadData(); // Перезагружаем данные из CSV-файла
    }

    protected function getTableHeaders(): array // Метод для получения заголовков таблицы
    {
        return ['ID', 'Фамилия', 'Имя', 'Отчество', 'Возраст', 'Действия']; // Возвращаем массив заголовков таблицы
    }

    protected function getDeleteLink(array $row): string // Метод для получения ссылки на удаление записи
    {
        $id = $row['id'] ?? ''; // Проверяем на null и присваиваем пустую строку, если значение null
        return "?deleteId=" . htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); // Возвращаем ссылку на удаление записи
    }
}