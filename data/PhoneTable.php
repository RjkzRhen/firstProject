<?php
namespace data; // Определяем пространство имен для класса

use db\Database as Db; // Импортируем класс Database из пространства имен db и задаем ему псевдоним Db
use PageInterface; // Импортируем интерфейс PageInterface
use config\Config; // Импортируем класс Config из пространства имен config

class PhoneTable extends AbstractTable implements PageInterface // Определяем класс PhoneTable, который наследует AbstractTable и реализует PageInterface
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
        $this->data = $this->db->getPhoneTableRows(); // Получаем строки таблицы из базы данных
    }
    protected function getTableHeaders(): array // Метод для получения заголовков таблицы
    {
        return ['ID', 'ФИО пользователя', 'Номер']; // Возвращаем массив заголовков таблицы
    }
    protected function getDeleteLink(array $row): string // Метод для получения ссылки на удаление записи
    {
        $id = $row['id'] ?? ''; // Проверяем на null и присваиваем пустую строку, если значение null
        return "?deleteId=" . htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); // Возвращаем ссылку на удаление записи
    }
    // Переопределяем метод getHtml, чтобы использовать другой шаблон
    public function getHtml(): string // Метод для получения HTML-кода таблицы
    {
        $htmlTemplate = file_get_contents(Config::getProjectDir() . '/templates/phone_table.html'); // Получаем HTML-шаблон таблицы
        return str_replace([ // Заменяем переменные в шаблоне
            '{{ style }}',
            '{{ table }}'
        ], [
            $this->getStyle(),
            $this->getHtmlTable()
        ], $htmlTemplate);
    }
}
