<?php
namespace data;

class ConcreteCSVTable extends CSVTable {
    public function render(): string {
        $html = "<table>\n";
        foreach ($this->data as $line) {
            $html .= "<tr>\n";
            foreach (str_getcsv($line, ';') as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>\n";
            }
            $html .= "</tr>\n";
        }
        $html .= "</table>\n";
        return $html;
    }
}