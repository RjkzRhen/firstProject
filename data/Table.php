<?php
namespace data;

use db\Database;
use PageInterface;

class Table extends AbstractTable implements PageInterface {
    private Database $db; // Объявление свойства для хранения объекта Database
    private int $minAge; // Объявление свойства для хранения минимального возраста

    public function __construct(Database $db, int $minAge = 0) {
        $this->db = $db; // Присваивание переданного объекта Database свойству $db
        $this->minAge = isset($_GET['minAge']) ? intval($_GET['minAge']) : 0; // Получение минимального возраста из GET-параметра
    }

    public function getHtml(): string {
        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang='en'>\n";
        $html .= "<head>\n";
        $html .= "<meta charset='UTF-8'>\n";
        $html .= "<title>Таблица пользователей</title>\n";
        $html .= $this->getStyle(); // Получение стилей для таблицы
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= $this->db->getTable($this->minAge); // Получение HTML-кода таблицы с учетом минимального возраста
        $html .= "<form action='' method='get'>\n";
        $html .= "<label for='minAge'>Минимальный возраст:</label>\n";
        $html .= "<input type='number' id='minAge' name='minAge' value='" . htmlspecialchars($this->minAge) . "'>\n";
        $html .= "<input type='submit' value='Фильтровать'>\n";
        $html .= "</form>\n";
        $html .= "</body>\n";
        $html .= "</html>";
        return $html; // Возвращение сгенерированного HTML-кода
    }

    public function loadData($filePath): void {
        // TODO: Implement loadData() method.
    }
}
