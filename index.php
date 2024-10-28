<?php
header('Content-Type: text/html; charset=utf-8');
include 'autoload.php'; // ����������� �������������� �������
require 'vendor/autoload.php'; // ����������� �������������� ������������

use config\Config;
use data\HomePage;
use data\Table;
use data\PhoneTable; // ��������� ����� �����
use db\Database;
use data\CSVTable;
use formsCSV\AddRecord;
use forms\Form;
use forms\PhoneForm; // ��������� ����� �����

/**
 * @throws Exception
 */
function router(string $uri): PageInterface {
    $config = new Config('config.ini'); // �������� ������� ������������
    $database = new Database($config); // �������� ������� ���� ������ � �������������� ������������
    $addRecordPage = new AddRecord($database, 'otherFiles/OpenDocument.csv'); // �������� ������� ��� ���������� ������ � CSV-����

    return match ($uri) {
        '/table' => new Table(new Database(new Config('config.ini'))), // �������� ������� ������� � �������������� ���� ������
        '/phone' => new PhoneTable(new Database(new Config('config.ini'))), // ��������� ����� �������
        '/csv' => new CSVTable('otherFiles/OpenDocument.csv'), // �������� ������� ������� ��� CSV-�����
        '/' => new HomePage(), // �������� ������� �������� ��������
        '/form' => new Form(new Database(new Config('config.ini'))), // �������� ������� ����� � �������������� ���� ������
        '/add_record' => $addRecordPage, // �������� ������� ��� ���������� ������ � CSV-����
        '/phone_form' => new PhoneForm(new Database(new Config('config.ini'))), // ��������� ����� ������� ��� ����� PhoneForm
        default => new NotFoundHttp() // �������� ������� �������� 404
    };
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // ��������� URI �������
$searchInPage = array_search($request, \config\Page::LINKS); // ����� ������������ URI � ������� ������
if ($searchInPage) {
    try {
        $result = router($request);
    } catch (Exception $e) {
    } // ����������� �������� �� ������ URI

    if (isset($_GET['deleteId'])) { // �������� ������� ��������� deleteId � GET-�������
        $config = new Config('config.ini'); // �������� ������� ������������
        $db = new Database($config); // �������� ������� ���� ������ � �������������� ������������
        $db->deleteRecord((int)$_GET['deleteId']); // �������� ������ �� ID
    }
    if (isset($_GET['delete_username'])) { // �������� ������� ��������� delete_username � GET-�������
        try {
            $csvTable = new CSVTable('otherFiles/OpenDocument.csv');
        } catch (Exception $e) {
        } // �������� ������� ������� ��� CSV-�����
        try {
            $csvTable->deleteByUsername($_GET['delete_username']);
        } catch (Exception $e) {
        } // �������� ������ �� ����� ������������
    }

    echo $result->getHtml(); // ����� HTML-���� ��������
}