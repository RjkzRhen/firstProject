<?php

namespace data;

use db\Database as Db;

// Класс для работы с таблицами базы данных
class Table extends AbstractTable
{
    private Db $db; // Свойство для хранения объекта Database

    public function __construct(Db $db)
    {
        parent::__construct(); // Вызов конструктора родительского класса
        $this->db = $db; // Присваивание объекта Database
        $this->loadData($this->db); // Загрузка данных из базы данных
    }

    public function loadData($source): void
    {
        $this->data = $this->db->getTableRows($this->minAge); // Загрузка данных как массива
    }

    public function getHtml(): string
    {
        $html = $this->getHtmlStart(); // Используем общий метод для начальной части HTML-кода
        $html .= "<table>\n" . $this->getTableHeaders(); // Добавление заголовков таблицы
        $html .= $this->getFilterForm(); // Используем общий метод для генерации формы фильтрации

        foreach ($this->data as $row) {
            $html .= $this->generateTableRow($row); // Генерация строки таблицы для каждого ряда данных
            $html .= "<td><a href='?deleteId=" . htmlspecialchars($row['id']) . "'>Удалить</a></td>\n</tr>\n"; // Добавляем ссылку на удаление
        }

        $html .= "</table>\n"; // Закрытие таблицы
        $html .= $this->getHtmlEnd(); // Используем общий метод для закрывающей части HTML-кода
        return $html; // Возвращение сгенерированного HTML-кода
    }
}