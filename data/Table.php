<?php
namespace data;
use db\Database;
use PageInterface;

class Table extends AbstractTable implements PageInterface
{
    private Database $db;
    private int $minAge;

    public function __construct(Database $db, int $minAge = 0)
    {
        $this->db = $db;
        $this->minAge = isset($_GET['minAge']) ? intval($_GET['minAge']) : 0;
    }

    public function loadData($filePath): void
    {
        // This method is not used in this class, but it's required by the abstract class
    }

    public function getHtml(): string
    {
        $html = "<!DOCTYPE html>\n";
        $html .= "<html lang='en'>\n";
        $html .= "<head>\n";
        $html .= "<meta charset='UTF-8'>\n";
        $html .= "<title>Таблица пользователей</title>\n";
        $html .= $this->getStyle();
        $html .= "</head>\n";
        $html .= "<body>\n";
        $html .= $this->db->getTable($this->minAge);
        $html .= "<form action='' method='get'>\n";
        $html .= "<label for='minAge'>Минимальный возраст:</label>\n";
        $html .= "<input type='number' id='minAge' name='minAge' value='" . htmlspecialchars($this->minAge) . "'>\n";
        $html .= "<input type='submit' value='Фильтровать'>\n";
        $html .= "</form>\n";
        $html .= "</body>\n";
        $html .= "</html>";
        return $html;
    }
}
