<?php
namespace data;

use Exception;
use PageInterface;

// Класс для работы с CSV-таблицами
class CSVTable extends AbstractTable {

    private CSVEditor $csvEditor; // Свойство для хранения объекта CSVEditor
    private CSVLoader $csvLoader; // Свойство для хранения объекта CSVLoader

    public function __construct(CSVLoader $csvLoader)
    {
        parent::__construct(); // Вызов конструктора родительского класса
        $this->csvLoader = $csvLoader; // Присваивание переданного объекта CSVLoader свойству $csvLoader
        $this->loadData(); // Загрузка данных из CSV-файла
        $this->csvEditor = new CSVEditor($csvLoader->getFilePath()); // Создание объекта CSVEditor с использованием пути к CSV-файлу
    }

    /**
     * @throws Exception
     */
    public function loadData(): void
    {
        $this->data = $this->csvLoader->loadData(); // Загрузка данных из CSV-файла
    }

    public function getHtml(): string
    {
        $html = $this->getHtmlStart(); // Используем общий метод для начальной части HTML-кода
        $html .= "<table>\n" . $this->getTableHeaders(); // Добавление заголовков таблицы
        $html .= $this->getFilterForm(); // Используем общий метод для генерации формы фильтрации

        $filteredData = $this->filterDataByMinAge($this->data); // Фильтрация данных по минимальному возрасту

        foreach ($filteredData as $index => $row) {
            if ($index == 0) continue; // Пропуск первой строки (заголовков)
            $html .= $this->generateTableRow($row); // Генерация строки таблицы
            $username = htmlspecialchars($row[0]); // Получение имени пользователя
            $html .= "<td><a href='?delete_username={$username}'>Удалить</a></td>\n</tr>\n"; // Добавление ссылки для удаления
        }
        $html .= "</table>\n"; // Закрытие таблицы
        $html .= $this->getHtmlEnd(); // Используем общий метод для закрывающей части HTML-кода
        return $html; // Возвращение сгенерированного HTML-кода
    }

    public function deleteByUsername($username): void
    {
        $this->csvEditor->deleteByUsername($username); // Удаление записи по имени пользователя
        $this->loadData(); // Перезагрузка данных после удаления
    }
}