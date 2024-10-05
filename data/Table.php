<?php
namespace data;

use db\Database;
use PageInterface;

// Класс для работы с таблицами базы данных
class Table extends AbstractTable implements PageInterface, DataLoaderInterface {
    private Database $db; // Свойство для хранения объекта Database

    // Конструктор класса
    public function __construct(Database $db) {
        $this->db = $db; // Присваивание переданного объекта Database свойству $db
        $this->minAge = isset($_GET['minAge']) ? intval($_GET['minAge']) : 0; // Получение минимального возраста из GET-параметра
        $this->loadData($this->db); // Загрузка данных из базы данных
    }

    // Метод для загрузки данных из базы данных
    public function loadData($source): void {
        $db = $source; // Присваиваем $source в $db для удобства
        $this->data = $db->getTableRows($this->minAge); // Загрузка данных из базы данных
    }

    // Метод для получения HTML-кода таблицы
    public function getHtml(): string {
        $html = $this->getHtmlStart(); // Используем общий метод для начальной части HTML-кода
        $html .= "<table>\n" . $this->getTableHeaders(); // Добавление заголовков таблицы
        $html .= $this->getFilterForm(); // Используем общий метод для генерации формы фильтрации
        $html .= $this->data; // Добавление строк таблицы
        $html .= "</table>\n"; // Закрытие таблицы
        $html .= $this->getHtmlEnd(); // Используем общий метод для закрывающей части HTML-кода
        return $html; // Возвращение сгенерированного HTML-кода
    }
}