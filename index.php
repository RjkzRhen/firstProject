<?php
include 'autoload.php';

use config\Config;
use data\HomePage;
use data\Table;
use db\Database;
use data\ConcreteCSVTable;
use data\CSVEditor;
use forms\CSVForm;

function router(string $uri): PageInterface
{
    $config = new Config('config.ini');
    $database = new Database($config);
    $csvEditor = new \data\CSVEditor('otherFiles/OpenDocument.csv');
    return match ($uri) {
        '/table' => new Table(new Database(new Config('config.ini'))),
        '/csv' => new ConcreteCSVTable('otherFiles/OpenDocument.csv'),
        '/' => new HomePage(),
        '/form' => new forms\Form(new Database(new Config('config.ini'))),
        '/csv_form' => new CSVForm($csvEditor),
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
        $csvTable = new ConcreteCSVTable('otherFiles/OpenDocument.csv');
        $csvTable->deleteByUsername($_GET['delete_username']);
    }

    echo $result->getHtml();
}
