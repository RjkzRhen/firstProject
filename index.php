<?php
include 'autoload.php'; // ����������� �������������� �������
require 'vendor/autoload.php'; // ����������� �������������� ������������

use config\Config;
use data\HomePage;
use data\Table;
use db\Database;
use data\CSVTable;
use data\CSVEditor;
use formsCSV\CSVWriter;
use formsCSV\AddRecord;

function router(string $uri): PageInterface {
    $config = new Config('config.ini'); // �������� ������� ������������
    $database = new Database($config); // �������� ������� ���� ������ � �������������� ������������
    $csvEditor = new \data\CSVEditor('otherFiles/OpenDocument.csv'); // �������� ������� ��� ������ � CSV-������
    $addRecordPage = new AddRecord('otherFiles/OpenDocument.csv'); // �������� ������� ��� ���������� ������ � CSV-����
    return match ($uri) {
        '/table' => new Table(new Database(new Config('config.ini'))), // �������� ������� ������� � �������������� ���� ������
        '/csv' => new CSVTable('otherFiles/OpenDocument.csv'), // �������� ������� ������� ��� CSV-�����
        '/' => new HomePage(), // �������� ������� �������� ��������
        '/form' => new forms\Form(new Database(new Config('config.ini'))), // �������� ������� ����� � �������������� ���� ������
        '/add_record' => new formsCSV\AddRecord('otherFiles/OpenDocument.csv'), // �������� ������� ��� ���������� ������ � CSV-����
        default => new NotFoundHttp() // �������� ������� �������� 404
    };
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // ��������� URI �������
$searchInPage = array_search($request, \config\Page::LINKS); // ����� ������������ URI � ������� ������
if ($searchInPage) {
    $result = router($request); // ����������� �������� �� ������ URI

    if (isset($_GET['deleteId'])) { // �������� ������� ��������� deleteId � GET-�������
        $config = new Config('config.ini'); // �������� ������� ������������
        $db = new Database($config); // �������� ������� ���� ������ � �������������� ������������
        $db->deleteRecord((int)$_GET['deleteId']); // �������� ������ �� ID
    }
    if (isset($_GET['delete_username'])) { // �������� ������� ��������� delete_username � GET-�������
        $csvTable = new CSVTable('otherFiles/OpenDocument.csv'); // �������� ������� ������� ��� CSV-�����
        $csvTable->deleteByUsername($_GET['delete_username']); // �������� ������ �� ����� ������������
    }
    if ($result instanceof formsCSV\AddRecord) { // ��������, �������� �� ��������� �������� AddRecord
        $result->handlePost(); // ��������� POST-�������
    }

    echo $result->getHtml(); // ����� HTML-���� ��������
}