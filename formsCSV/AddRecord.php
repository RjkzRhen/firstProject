<?php
namespace formsCSV;

use forms\AbstractForm;
use db\Database;
use formsCSV\CSVWriter;
use PageInterface;

class AddRecord extends AbstractForm implements PageInterface {
    public function __construct(Database $db) {
        parent::__construct($db); // ����� ������������ ������������� ������
    }

    protected function getTemplate(): array {
        return [
            ['id' => 'username', 'name' => 'username', 'label' => '��� ������������', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'lastname', 'name' => 'lastname', 'label' => '�������', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'firstname', 'name' => 'firstname', 'label' => '���', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'middlename', 'name' => 'middlename', 'label' => '��������', 'type' => 'text', 'value' => '', 'required' => true, 'isValid' => true],
            ['id' => 'age', 'name' => 'age', 'label' => '�������', 'type' => 'number', 'value' => '', 'required' => true, 'isValid' => true]
        ]; // ����������� ������� ����� �����
    }

    public function getHtml(): string {
        $fields = $this->handleRequest(); // ��������� ������� � ��������� ����� �����

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->isAllValid($fields)) {
            $this->insertIntoTable($fields, $this->db->conn); // ������� ������ � �������, ���� ��� ���� �������
        }

        $html = '<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8">
    <title>Add an entry to the table</title>
    <style type="text/css">
        .error { border: 2px solid #ff0000; }
        .req:valid { border: 2px solid #000000; }
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); width: 400px; }
        label { font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        input.error { border: 2px solid #ff0000; } 
        input.req { outline: none; }
        input[type="submit"] { background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <form method="post">';

        foreach ($fields as $field) { // ������� ����� ��� �������� ��������� �����.
            $class = $field['isValid'] ? "req" : "error";  // ���������� ������ � ����������� �� ����������.
            $html .= '<label for="' . $field['name'] . '">' . ucfirst($field['name']) . ':</label>';
            $html .= '<input type="text" name="' . $field['name'] . '" value="' . htmlspecialchars($field['value']) . '" class="' . $class . '"><br>';
        }

        $html .= '<input type="submit" value="Add an entry to the table">
    </form>
</body>
</html>';

        return $html; // ����������� ���������������� HTML ����.
    }
}