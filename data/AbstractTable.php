<?php
namespace data; // Определяем пространство имен для класса

use config\Config; // Импортируем класс Config из пространства имен config

abstract class AbstractTable implements DataLoaderInterface // Определяем абстрактный класс AbstractTable, который реализует DataLoaderInterface
{
    protected array $data = []; // Свойство для хранения данных таблицы
    protected int $minAge; // Свойство для хранения минимального возраста

    public function __construct() // Конструктор класса
    {
        $this->minAge = isset($_GET['minAge']) ? intval($_GET['minAge']) : 0; // Получаем минимальный возраст из GET-параметра или устанавливаем его в 0
        $this->loadData(); // Загружаем данные
    }

    abstract public function loadData(): void; // Объявляем абстрактный метод loadData
    abstract protected function getTableHeaders(): array; // Объявляем абстрактный метод getTableHeaders
    abstract protected function getDeleteLink(array $row): string; // Объявляем абстрактный метод getDeleteLink

    public function getHtml(): string // Метод для получения HTML-кода таблицы
    {
        $htmlTemplate = file_get_contents(Config::getProjectDir() . '/templates/table.html'); // Получаем HTML-шаблон таблицы
        return str_replace([ // Заменяем переменные в шаблоне
            '{{ style }}',
            '{{ table }}',
            '{{ minAge }}'
        ], [
            $this->getStyle(),
            $this->getHtmlTable(),
            htmlspecialchars($this->minAge)
        ], $htmlTemplate);
    }

    protected function getHtmlTable(): string // Метод для получения HTML-кода таблицы
    {
        $html = "<table>"; // Начинаем таблицу
        $html .= $this->getTableHeadersHtml(); // Добавляем заголовки таблицы
        $html .= $this->getTableBodyHtml(); // Добавляем тело таблицы
        $html .= "</table>"; // Заканчиваем таблицу
        return $html; // Возвращаем HTML-код таблицы
    }

    final protected function getStyle(): string // Метод для получения стилей таблицы
    {
        return "
        <style>
            body { font-family: 'Arial', sans-serif; background-color: #f7f7f7; margin: 0; padding: 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f2f2f2; color: #333; text-transform: uppercase; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            tr:hover { background-color: #f1f1f1; }
            .age-over-50 { color: red; font-weight: bold; }
        </style>";
    }

    protected function filterDataByMinAge(array $data): array
    {
        return array_filter($data, function ($row) {
            return isset($row[4]) && (int)$row[4] >= $this->minAge;
        });
    }
    protected function getTableHeadersHtml(): string // Метод для получения HTML-кода заголовков таблицы
    {
        $headers = ''; // Инициализируем строку для заголовков
        foreach ($this->getTableHeaders() as $header) { // Перебираем заголовки
            $headers .= "<th>{$header}</th>"; // Добавляем заголовок в строку
        }
        return "<tr>{$headers}</tr>\n"; // Возвращаем HTML-код заголовков таблицы
    }

    protected function getTableBodyHtml(): string // Метод для получения HTML-кода тела таблицы
    {
        $html = ''; // Инициализируем строку для тела таблицы
        foreach ($this->data as $row) { // Перебираем строки данных
            $html .= $this->generateTableRow($row); // Генерируем HTML-код для строки
        }
        return $html; // Возвращаем HTML-код тела таблицы
    }

    protected function generateTableRow(array $row): string // Метод для генерации HTML-кода строки таблицы
    {
        $html = "<tr>\n"; // Начинаем строку таблицы
        foreach ($row as $cellIndex => $cell) { // Перебираем ячейки строки
            $html .= $this->generateTableCell($cellIndex, $cell, true); // Генерируем HTML-код для ячейки
        }
        $html .= "<td><a href='{$this->getDeleteLink($row)}'>Удалить</a></td>\n"; // Добавляем ссылку "Удалить"
        $html .= "</tr>\n"; // Заканчиваем строку таблицы
        return $html; // Возвращаем HTML-код строки таблицы
    }

    protected function generateTableCell(mixed $cellIndex, mixed $cell, bool $addAgeClass = false): string // Метод для генерации HTML-кода ячейки таблицы
    {
        $class = ''; // Инициализируем строку для класса
        if ($addAgeClass && $cellIndex === 4 && (int)$cell > 50) { // Проверяем условия для добавления класса age-over-50
            $class = ' class="age-over-50"'; // Добавляем класс age-over-50
        }
        return "<td{$class}>" . htmlspecialchars($cell) . "</td>"; // Возвращаем HTML-код ячейки таблицы
    }

    protected function convertEncoding($value): array|false|string|null // Метод для преобразования кодировки значения
    {
        return mb_convert_encoding($value, 'UTF-8', 'auto'); // Возвращаем значение, преобразованное в UTF-8
    }
}