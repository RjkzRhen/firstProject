<?php
include 'autoload.php';

use config\Config;
use data\HomePage;
use data\Table;
use db\Database;
use data\CSVTable;
use data\CSVEditor;
use config\Page;
function router(string $uri): PageInterface
{
    return match ($uri) {
        '/table' => new Table(new Database(new Config('config.ini'))),
        '/csv' => new data\ConcreteCSVTable('otherFiles/OpenDocument.csv'),
        '/' => new HomePage(),
        '/form' => new \forms\Form(new Database(new Config('config.ini'))),
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
            $csvTable = new data\ConcreteCSVTable('otherFiles/OpenDocument.csv'); // Changed comma to semicolon here
            $csvTable->deleteByUsername($_GET['delete_username']);
        }

        echo $result->getHtml();
    }