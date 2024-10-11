<?php
namespace data;

use Exception;
use PageInterface;

// Класс для работы с CSV-таблицами
class CSVTable extends AbstractTable implements PageInterface {
    private CSVEditor $csvEditor; // Свойство для хранения объекта CSVEditor
    private $filePath; // Свойство для хранения пути к CSV-файлу

    // Конструктор класса
    public function __construct($filePath) {
        $this->filePath = $filePath; // Присваивание переданного пути к CSV-файлу свойству $filePath// Загрузка данных из CSV-файла
        $this->csvEditor = new CSVEditor($filePath); // Создание объекта CSVEditor с использованием пути к CSV-файлу
        $this->minAge = isset($_GET['minAge']) ? (int)$_GET['minAge'] : 0; // Инициализация свойства $minAge

        parent::__construct();
    }

    // Метод для загрузки данных из CSV-файла

    /**
     * @throws Exception
     */
    public function loadData(): void
    {
        if (!file_exists($this->filePath)) { // Проверка существования файла
            throw new Exception("Файл не найден: " . $this->filePath); // Выброс исключения, если файл не найден
        }

        $handle = fopen($this->filePath, 'rb'); // Открытие CSV-файла для чтения
        if ($handle === false) {
            throw new Exception("Ошибка при открытии файла: " . $this->filePath); // Выброс исключения в случае ошибки
        }

        $this->data = [];
        while (($line = fgetcsv($handle, 1000, ";")) !== false) { // Чтение строк из CSV-файла
            $line = array_map([$this, 'convertEncoding'], $line); // Конвертация кодировки значений
            $this->data[] = $line; // Добавление строки в массив данных
        }
        fclose($handle); // Закрытие файла
    }

    // Метод для конвертации кодировки значений
    public function convertEncoding($value): array|false|string|null
    {
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
    }

    // Метод для получения HTML-кода таблицы
//    public function getHtml(): string {
//        $html = $this->getHtmlStart(); // Используем общий метод для начальной части HTML-кода
//        $html .= "<table>\n" . $this->getTableHeaders(); // Добавление заголовков таблицы
//        $html .= $this->getFilterForm(); // Используем общий метод для генерации формы фильтрации
//
//        $filteredData = $this->filterDataByMinAge($this->data); // Фильтрация данных по минимальному возрасту
//
//        foreach ($filteredData as $index => $row) { // Перебор строк данных
//            if ($index == 0) continue; // Пропуск первой строки (заголовков)
//            $html .= $this->generateTableRow($row); // Генерация строки таблицы
//            $username = htmlspecialchars($row[0]); // Получение имени пользователя
//            $html .= "<td><a href='?delete_username={$username}'>Удалить</a></td>\n</tr>\n"; // Добавление ссылки для удаления
//        }
//        $html .= "</table>\n"; // Закрытие таблицы
//        $html .= $this->getHtmlEnd(); // Используем общий метод для закрывающей части HTML-кода
//        return $html; // Возвращение сгенерированного HTML-кода
//    }

    // Метод для удаления записи по имени пользователя

    /**
     * @throws Exception
     */
    public function deleteByUsername($username): void {
        $this->csvEditor->deleteByUsername($username); // Удаление записи по имени пользователя
        $this->loadData($this->filePath); // Перезагрузка данных после удаления
    }

    protected function getTableHeaders(): array
    {
        return ['ID', 'Фамилия', 'Имя', 'Отчество', 'Возраст', 'Действия'];
    }

    protected function generateTableCell(mixed $rowIndex, mixed $cellIndex, mixed $cell): string
    {
        $class = '';
        if ($cellIndex === 4 && (int)$cell > 50) {
            $class = ' class="age-over-50"';
        }
        return "<td $class>" . htmlspecialchars($cell) . "</td>";
    }
}