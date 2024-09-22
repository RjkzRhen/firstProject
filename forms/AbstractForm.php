<?php
namespace forms;

use db\Database;

abstract class AbstractForm {
    protected array $fields; // ���������� �������� ��� �������� ����� �����
    protected Database $db; // ���������� �������� ��� �������� ������� Database

    public function __construct(Database $db) {
        $this->db = $db; // ������������ ����������� ������� Database �������� $db
        $this->fields = $this->handleRequest(); // ��������� ������� � ��������� ����� �����
    }

    abstract protected function getTemplate(): array; // ����������� ����� ��� ��������� ������� ����� �����

    protected function handleRequest(): array {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // ��������, ���� �� ���������� �����
            $fields = $this->getDataFromFormAndUpdateTemplate(); // ��������� � ��������� ������ �����
        } else {
            $fields = $this->getTemplate(); // �������� ������ ����� � ������� ����������, ���� ����� �� ����������
        }
        return $fields; // ���������� ���� �����
    }

    protected function getDataFromFormAndUpdateTemplate(): array {
        $fields = $this->getTemplate(); // ��������� ������� ����� �����
        $result = [];
        foreach ($fields as $field) { // ������� ����� �����
            $field['value'] = $_POST[$field['name']] ?? ''; // ��������� �������� ���� �� POST-������� ��� ������ ������
            $field['isValid'] = !empty($field['value']); // ��������� ����� ���������� � true, ���� ���� �� ������
            $result[] = $field; // ���������� ���� � �������������� ������
        }
        return $result; // ����������� ������������ ������� ����� �����
    }

    protected function isAllValid(array $dataTemplate): bool {
        foreach ($dataTemplate as $field) { // ������� ���� ����� � ������� ������
            if ($field['required'] && empty($field['value'])) { // ��������, �������� �� ���� ������������ � ������
                return false; // ���� ���� ���������, ���������� false
            }
        }
        return true; // ���� ��� ���� �������, ���������� true
    }

    abstract public function getHtml(): string; // ����������� ����� ��� ��������� HTML-���� �����

    protected function insertIntoTable(array $dataTemplate, $con): void {
        if ($this instanceof \formsCSV\AddRecord) {
            // ���������� ������ ��� ������ � CSV.
            $data = array_map(function ($field) {
                return $field['value'];
            }, $dataTemplate);

            // ������� ������ � CSV ����.
            try {
                $csvWriter = new \formsCSV\CSVWriter('otherFiles/OpenDocument.csv');
                $csvWriter->addRecord($data);
                // ��������������� �� �������� CSV-�������.
                header("Location: /csv");
                exit;
            } catch (\Exception $e) { // ��������� ��������� ����������.
                echo "Error: " . $e->getMessage();
            }
        } else {
            $columns = implode(", ", array_map(function($item) {
                return "`" . $item['name'] . "`";
            }, $dataTemplate)); // ������������ ������ � ������� ��������

            $values = implode(", ", array_map(function($item) use ($con) {
                return "'" . $con->real_escape_string($item['value']) . "'";
            }, $dataTemplate)); // ������������ ������ �� ����������, ��������������� ��� ������������

            $sql = "INSERT INTO `name` ($columns) VALUES ($values)"; // ������������ SQL-������� ��� ������� ������

            if ($con->query($sql)) { // ���������� SQL-�������
                header("Location: /table"); // ��������������� �� �������� �������
                exit; // ���������� �������
            } else {
                echo "������: " . $sql . "<br>" . $con->error; // ����� ������, ���� ������ �� ��������
            }
        }
    }
}