<?php
header('Content-Type: text/html; charset=utf-8');
include 'autoload.php'; // Подключение автозагрузчика классов
require 'vendor/autoload.php'; // Подключение автозагрузчика зависимостей

use config\Config;
use data\HomePage;
use data\Table;
use db\Database;
use data\CSVTable;
use formsCSV\AddRecord;
use forms\Form;

/**
 * @throws Exception
 */
function router(string $uri): PageInterface {
    $config = new Config('config.ini'); // Создание объекта конфигурации
    $database = new Database($config); // Создание объекта базы данных с использованием конфигурации
    $csvEditor = new \data\CSVEditor('otherFiles/OpenDocument.csv'); // Создание объекта для работы с CSV-файлом
    $addRecordPage = new AddRecord($database, 'otherFiles/OpenDocument.csv'); // Создание объекта для добавления записи в CSV-файл

    return match ($uri) {
        '/table' => new Table(new Database(new Config('config.ini'))), // Создание объекта таблицы с использованием базы данных
        '/csv' => new CSVTable('otherFiles/OpenDocument.csv'), // Создание объекта таблицы для CSV-файла
        '/' => new HomePage(), // Создание объекта домашней страницы
        '/form' => new Form(new Database(new Config('config.ini'))), // Создание объекта формы с использованием базы данных
        '/add_record' => $addRecordPage, // Создание объекта для добавления записи в CSV-файл
        default => new NotFoundHttp() // Создание объекта страницы 404
    };
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Получение URI запроса
$searchInPage = array_search($request, \config\Page::LINKS); // Поиск соответствия URI в массиве ссылок
if ($searchInPage) {
    try {
        $result = router($request);
    } catch (Exception $e) {
    } // Определение страницы на основе URI

    if (isset($_GET['deleteId'])) { // Проверка наличия параметра deleteId в GET-запросе
        $config = new Config('config.ini'); // Создание объекта конфигурации
        $db = new Database($config); // Создание объекта базы данных с использованием конфигурации
        $db->deleteRecord((int)$_GET['deleteId']); // Удаление записи по ID
    }
    if (isset($_GET['delete_username'])) { // Проверка наличия параметра delete_username в GET-запросе
        try {
            $csvTable = new CSVTable('otherFiles/OpenDocument.csv');
        } catch (Exception $e) {
        } // Создание объекта таблицы для CSV-файла
        try {
            $csvTable->deleteByUsername($_GET['delete_username']);
        } catch (Exception $e) {
        } // Удаление записи по имени пользователя
    }

    echo $result->getHtml(); // Вывод HTML-кода страницы
}