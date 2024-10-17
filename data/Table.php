<?php
namespace data; // Определяем пространство имен для класса

use db\Database as Db; // Импортируем класс Database из пространства имен db и задаем ему псевдоним Db
use PageInterface; // Импортируем интерфейс PageInterface

class Table extends AbstractTable implements PageInterface // Определяем класс Table, который наследует AbstractTable и реализует PageInterface
{
    private Db $db; // Свойство для хранения объекта базы данных

    public function __construct(Db $db) // Конструктор класса, принимает объект базы данных
    {
        $this->db = $db; // Присваиваем объект базы данных свойству класса
        parent::__construct(); // Вызываем конструктор родительского класса
        $this->loadData(); // Загружаем данные из базы данных
    }

    public function loadData(): void // Метод для загрузки данных из базы данных
    {
        $this->data = $this->db->getTableRows($this->minAge); // Получаем строки таблицы из базы данных

    }

    protected function getTableHeaders(): array // Метод для получения заголовков таблицы
    {
        return ['ID', 'Фамилия', 'Имя', 'Отчество', 'Возраст', 'Действия']; // Возвращаем массив заголовков таблицы
    }

    protected function getDeleteLink(array $row): string // Метод для получения ссылки на удаление записи
    {
        return "?deleteId=" . htmlspecialchars($row['id']); // Возвращаем ссылку на удаление записи
    }
}