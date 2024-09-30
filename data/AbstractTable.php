<?php

namespace data;

abstract class AbstractTable
{
    protected $data; // Объявление свойства для хранения данных таблицы
    protected int $minAge; // Объявление свойства для хранения минимального возраста

    const array TABLE_HEADERS = ['ID', 'Фамилия', 'Имя', 'Отчество', 'Возраст', 'Действия']; // Константа с заголовками столбцов таблицы

    abstract public function loadData($filePath): void; // Объявление абстрактного метода для загрузки данных

    abstract public function getHtml(): string; // Объявление абстрактного метода для получения HTML-кода таблицы

    protected function getStyle(): string
    {
        return "<style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .age-over-50 {
            color: red;
            font-weight: bold;
        }
        </style>"; // Возвращает строку с CSS-стилями для таблицы
    }

    // Добавляем общий метод для генерации начальной части HTML-кода
    protected function getHtmlStart(): string
    {
        return "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<title>Table</title>\n" . $this->getStyle() . "</head>\n<body>\n"; // Возвращает строку с начальной частью HTML-документа
    }

    // Добавляем общий метод для генерации закрывающей части HTML-кода
    protected function getHtmlEnd(): string
    {
        return "</body>\n</html>"; // Возвращает строку с закрывающей частью HTML-документа
    }

    // Метод для фильтрации данных по минимальному возрасту
    protected function filterDataByMinAge($data): array
    {
        return array_filter($data, function ($row) {
            return isset($row[4]) && (int)$row[4] >= $this->minAge; // Фильтрует данные, оставляя только те строки, где возраст больше или равен минимальному
        });
    }

    // Метод для генерации формы фильтрации по минимальному возрасту
    protected function getFilterForm(): string
    {
        return "<form action='' method='get'>\n" .
            "<label for='minAge'>Минимальный возраст:</label>\n" .
            "<input type='number' id='minAge' name='minAge' value='" . htmlspecialchars($this->minAge) . "'>\n" .
            "<input type='submit' value='Фильтровать'>\n" .
            "</form>\n"; // Возвращает строку с HTML-кодом формы фильтрации по минимальному возрасту
    }

    // Метод для конвертации кодировки строки в UTF-8
    protected function convertEncoding($value): string
    {
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1251'); // Конвертирует строку из кодировки Windows-1251 в UTF-8
    }

    // Метод для получения заголовков столбцов таблицы
    protected function getTableHeaders(): string
    {
        $headers = '';
        foreach (self::TABLE_HEADERS as $header) {
            $headers .= "<th>{$header}</th>"; // Генерирует строку с заголовками столбцов таблицы
        }
        return "<tr>{$headers}</tr>\n"; // Возвращает строку с HTML-кодом заголовков таблицы
    }

    // Метод для генерации строки таблицы
    protected function generateTableRow(array $row): string
    {
        $html = "<tr>\n";
        foreach ($row as $cellIndex => $cell) {
            $style = '';
            if ($cellIndex == 4 && (int)$cell > 50) { // Проверка возраста и добавление класса для стилизации
                $style = ' class="age-over-50"';
            }
            $html .= "<td" . $style . ">" . htmlspecialchars($cell) . "</td>\n"; // Добавление ячейки в HTML
        }
        return $html; // Возвращает строку с HTML-кодом строки таблицы
    }
}