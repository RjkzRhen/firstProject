<?php
namespace forms;

use AllowDynamicProperties;
use data\CSVEditor;
use PageInterface;

#[AllowDynamicProperties] class CSVForm implements PageInterface
{
    private $insertForm;

    private CSVEditor $csvEditor;

    public function __construct(CSVEditor $csvEditor) {
        $this->csvEditor = $csvEditor;
        $this->insertForm = new InsertForm();
        $this->fields = $this->insertForm->handleRequest();
        if ($this->isAllValid($this->fields)) {
            $this->insertForm->insertIntoCSV($this->fields, $this->csvEditor);
        }
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->insertForm->insertIntoCSV($_POST);
            header("Location: /csv");
            exit;
        }
    }

    public function isAllValid($fields)
    {
        if (!is_array($fields)) {
            return false; // ��������, ��� fields �������� ��������
        }
        foreach ($fields as $field) {
            if (!is_array($field) || !isset($field['isValid']) || !$field['isValid']) {
                return false; // ��������, ��� ������ field �������� �������� � �������� ���� isValid
            }
        }
        return true; // ��� ���� �������������
    }

    public function getHtml(): string
    {
        $fields = $this->insertForm->getDataFromFormAndUpdateTemplate();  // Corrected property name here
        $isValid = $this->isAllValid($fields);

        $html = '<!DOCTYPE html><html lang="en"><head>
        <meta charset="UTF-8">
        <title>���������� ������ � CSV</title>
        <style type="text/css">
            .error { border: 2px solid #ff0000; }
            .req:valid { border: 2px solid #000000; }
            body { font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
            form { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); width: 400px; }
            label { font-weight: bold; margin-bottom: 5px; }
            input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
            input.req { outline: none; }
            input[type="submit"] { background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        </style>
    </head>
    <body>
        <form action="" method="post" id="csvForm">';
        $html .= '<form action="/form_csv" method="post" id="userForm">';
        foreach ($this->fields as $field) {
            $class = $field['isValid'] ? "req" : "error";
            $html .= '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>';
            $html .= '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'"><br>';
        }
        $html .= '<input type="submit" name="submit" value="�������� ������" id="button">';
        $html .= '</form>';;
        return $html;
    }
}