<?php

namespace data;

// Абстрактный класс для таблиц
use config\Config;

abstract class AbstractTable implements DataLoaderInterface
{

    public function __construct()
    {
        $this->loadData();
    }

    protected array $data; // Свойство для хранения данных таблицы
    protected int $minAge; // Свойство для хранения минимального возраста

//    const TABLE_HEADERS = ['ID', 'Фамилия', 'Имя', 'Отчество', 'Возраст', 'Действия']; // Константа с заголовками столбцов таблицы

    abstract protected function getTableHeaders(): array;


    //abstract public function getHtml(): string; // Абстрактный метод для получения HTML-кода таблицы

    public function getHtml(): string
    {
        $html = file_get_contents(Config::getProjectDir() . '/templates/table.html');
        return str_replace([
            '{{ style }}',
            '{{ table }}',
        ], [
            $this->getStyle(),
            $this->getHtmlTable(),
        ],
            $html
        );
    }

    protected function getHtmlTable(): string
    {
        $html = "<table>";
        $html .= $this->getTableHeadersHtml();
        $html .= $this->getTableBodyHtml();
        $html .= "</table>";
        return $html;
    }
    // Метод для получения CSS-стилей
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
        </style>";
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
            "</form>\n";
    }

    // Метод для получения заголовков столбцов таблицы
    protected function getTableHeadersHtml(): string
    {
        $headers = '';
        foreach ($this->getTableHeaders() as $header) {
            $headers .= "<th>{$header}</th>"; // Генерирует строку с заголовками столбцов таблицы
        }
        return "<tr>{$headers}</tr>\n";
    }

    protected function getTableBodyHtml(): string
    {
        $html = '';
        foreach ($this->data as $key => $row) {
            $html .= '<tr>';
            foreach ($row as $cellIndex => $cell) {
                $html .= $this->generateTableCell($key, $cellIndex, $cell);
            }
            $html .= '</tr>';

        }
        return $html;
    }

    protected function generateTableCell(mixed $rowIndex, mixed $cellIndex, mixed $cell): string
    {
        return "<td>" . htmlspecialchars($cell) . "</td>";
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
        return $html;
    }
}