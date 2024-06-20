<?php
include_once '../db/Database.php';
include_once '../forms/InsertForm.php';

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/table':
        include 'data/index.php';
        break;
    case '/form':
        include 'forms/form.php';
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>