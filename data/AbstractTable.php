<?php
namespace data;
// Абстрактный класс для таблиц
use PageInterface;
abstract class AbstractTable implements PageInterface, DataLoaderInterface
{
    protected array $data = []; // Свойство для хранения данных таблицы
    protected int $minAge;      // Свойство для фильтрации по минимальному возрасту
    public const TABLE_HEADERS = ['ID', 'Фамилия', 'Имя', 'Отчество', 'Возраст', 'Действия']; // Константа с заголовками таблицы
    public function __construct()
    {
        $this->minAge = isset($_GET['minAge']) ? intval($_GET['minAge']) : 0; // Инициализация свойства $minAge из GET-параметра или по умолчанию 0
    }
    // Абстрактный метод для загрузки данных
    abstract public function loadData($source): void; // Метод должен быть реализован в дочерних классах для загрузки данных из источника
    // Абстрактный метод для получения HTML-кода таблицы
    abstract public function getHtml(): string; // Метод должен быть реализован в дочерних классах для генерации HTML-кода таблицы
    /**
     * Финализированный метод для получения CSS-стилей
     */
    final protected function getStyle(): string
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

    /**
     * Генерация начальной части HTML-кода
     * Используется для начала формирования страницы
     */
    final protected function getHtmlStart(): string
    {
        return "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<title>Table</title>\n" . $this->getStyle() . "</head>\n<body>\n";
    }

    /**
     * Генерация закрывающей части HTML-кода
     * Используется для завершения страницы
     */
    final protected function getHtmlEnd(): string
    {
        return "</body>\n</html>";
    }

    /**
     * Фильтрация данных по минимальному возрасту
     * Логика фильтрации будет одинаковой для всех таблиц
     */
    final protected function filterDataByMinAge(array $data): array
    {
        return array_filter($data, function ($row) {
            // Предполагаем, что возраст находится в 5-м столбце (индекс 4)
            return isset($row[4]) && (int)$row[4] >= $this->minAge; // Фильтрация данных по минимальному возрасту
        });
    }

    /**
     * Генерация формы фильтрации по минимальному возрасту
     * Общая форма для всех таблиц
     */
    final protected function getFilterForm(): string
    {
        return "<form action='' method='get'>
                    <label for='minAge'>Минимальный возраст:</label>
                    <input type='number' id='minAge' name='minAge' value='" . htmlspecialchars($this->minAge) . "'>
                    <input type='submit' value='Фильтровать'>
                </form>\n";
    }

    /**
     * Генерация заголовков таблицы
     * Общий для всех таблиц
     */
    final protected function getTableHeaders(): string
    {
        $headers = '';
        foreach (static::TABLE_HEADERS as $header) {
            $headers .= "<th>{$header}</th>"; // Генерация заголовков
        }
        return "<tr>{$headers}</tr>\n";
    }

    /**
     * Генерация строки таблицы
     * Включает стилизацию возраста для пользователей старше 50
     */
    final protected function generateTableRow(array $row): string
    {
        $html = "<tr>\n";
        foreach ($row as $cellIndex => $cell) {
            $style = '';
            // Если возраст больше 50, добавляем класс для стилизации
            if ($cellIndex == 4 && (int)$cell > 50) {
                $style = ' class="age-over-50"';
            }
            $html .= "<td{$style}>" . htmlspecialchars($cell) . "</td>\n"; // Экранирование значений
        }
        return $html;
    }
}