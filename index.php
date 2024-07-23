<?php
include 'autoload.php';
require_once 'data/CSVTable.php';

use config\Config;
use data\HomePage;
use data\Table;
use db\Database;
use data\CSVTable;


$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$searchInPage = array_search($request, \config\Page::LINKS);
if ($searchInPage) {
    $result = router($request);


    if (isset($_GET['deleteId'])) {
        $config = new Config('config.ini');
        $db = new Database($config);
        $db->deleteRecord((int)$_GET['deleteId']);
    }

    echo $result->getHtml();

}

function router(string $uri): PageInterface
{
    return match ($uri) {
        '/table' => (new Table(new Database(new Config('config.ini')))),
        '/csv' => (new CSVTable('otherFiles/OpenDocument.csv')),
        '/' => (new HomePage()),
        '/form' => (new \forms\Form(new Database(new Config('config.ini')))),
        default => new NotFoundHttp()
    };
}