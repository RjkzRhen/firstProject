<?php
namespace data;

use db\Database;
use PageInterface;

class Table extends AbstractTable implements PageInterface {
    private Database $db; // Объявление свойства для хранения объекта Database

    public function __construct(Database $db) {
        $this->db = $db; // Присваивание переданного объекта Database свойству $db
        $this->minAge = isset($_GET['minAge']) ? intval($_GET['minAge']) : 0; // Получение минимального возраста из GET-параметра
    }

    public function getHtml(): string {
        $html = $this->getHtmlStart(); // Используем общий метод для начальной части HTML-кода
        $html .= "<table>\n" . $this->getTableHeaders(); // Добавление заголовков таблицы
        $html .= $this->getFilterForm(); // Используем общий метод для генерации формы фильтрации
        $html .= $this->db->getTableRows($this->minAge); // Получение строк таблицы с учетом минимального возраста
        $html .= "</table>\n"; // Закрытие таблицы
        $html .= $this->getHtmlEnd(); // Используем общий метод для закрывающей части HTML-кода
        return $html; // Возвращение сгенерированного HTML-кода
    }
}