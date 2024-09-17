<?php
include 'autoload.php';
require 'vendor/autoload.php';

use config\Config;
use data\HomePage;
use data\Table;
use db\Database;
use data\CSVTable;
use data\CSVEditor;
use formsCSV\CSVWriter;
use formsCSV\AddRecord;

function router(string $uri): PageInterface
{
    $config = new Config('config.ini');
    $database = new Database($config);
    $csvEditor = new \data\CSVEditor('otherFiles/OpenDocument.csv');
    $addRecordPage = new AddRecord('otherFiles/OpenDocument.csv');
    return match ($uri) {
        '/table' => new Table(new Database(new Config('config.ini'))),
        '/csv' => new CSVTable('otherFiles/OpenDocument.csv'),
        '/' => new HomePage(),
        '/form' => new forms\Form(new Database(new Config('config.ini'))),
        '/add_record' => new formsCSV\AddRecord('otherFiles/OpenDocument.csv'),
        default => new NotFoundHttp()
    };
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$searchInPage = array_search($request, \config\Page::LINKS);
if ($searchInPage) {
    $result = router($request);

    if (isset($_GET['deleteId'])) {
        $config = new Config('config.ini');
        $db = new Database($config);
        $db->deleteRecord((int)$_GET['deleteId']);
    }
    if (isset($_GET['delete_username'])) {
        $csvTable = new CSVTable('otherFiles/OpenDocument.csv');
        $csvTable->deleteByUsername($_GET['delete_username']);
    }
    if ($result instanceof formsCSV\AddRecord) {
        $result->handlePost();
    }

    echo $result->getHtml();
}
