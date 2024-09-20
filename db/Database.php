<?php
namespace db;

use config\Config;
use mysqli;
use mysqli_stmt;

$config = new Config('config.ini'); // Создание объекта конфигурации
$db = new Database($config); // Создание объекта базы данных с использованием конфигурации

class Database {
    public ?mysqli $conn; // Объявление свойства для хранения соединения с базой данных

    private Config $config; // Объявление свойства для хранения объекта конфигурации

    public function __construct(Config $config) {
        $this->config = $config; // Присваивание переданного объекта конфигурации свойству $config
        $this->conn = $this->getConnection(); // Получение соединения с базой данных и присваивание его свойству $conn
    }

    private function getConnection() {
        $config = new Config('config.ini'); // Создание объекта конфигурации (дублирование, можно удалить)

        $conn = new mysqli($this->config->getServername(), $this->config->getUsername(), $this->config->getPassword(), $this->config->getDbname()); // Создание соединения с базой данных

        if ($conn->connect_error) { // Проверка на ошибку соединения
            die("Ошибка подключения: " . $conn->connect_error); // Вывод ошибки и завершение скрипта
            $this->conn->set_charset("utf8"); // Установка кодировки (недостижимый код, можно удалить)
        }
        return $conn; // Возвращение соединения
    }

    public function executeSQL($sql, $params = null): false|mysqli_stmt {
        $stmt = $this->conn->prepare($sql); // Подготовка SQL-запроса
        if ($params) { // Если переданы параметры
            $stmt->bind_param($params['types'], ...$params['values']); // Привязка параметров к запросу
        }
        $stmt->execute(); // Выполнение запроса
        return $stmt; // Возвращение объекта подготовленного запроса
    }

    public function getTable(int $minAge): string {
        $rows = $this->getTableRows($minAge); // Получение строк таблицы с учетом минимального возраста
        $tableHtml = "<table>\n";
        $tableHtml .= "<tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Возраст</th><th>Действия</th></tr>\n";
        foreach ($rows as $row) { // Перебор строк таблицы
            $ageClass = $row['age'] > 50 ? 'age-over-50' : ''; // Определение класса для возраста
            $tableHtml .= "<tr>\n";
            $tableHtml .= "<td>{$row['id']}</td>\n";
            $tableHtml .= "<td>{$row['last_name']}</td>\n";
            $tableHtml .= "<td>{$row['first_name']}</td>\n";
            $tableHtml .= "<td>{$row['middle_name']}</td>\n";
            $tableHtml .= "<td class='{$ageClass}'>{$row['age']}</td>\n";
            $tableHtml .= "<td><a href='?deleteId={$row['id']}'>Удалить</a></td>\n";
            $tableHtml .= "</tr>\n";
        }
        $tableHtml .= "</table>\n";
        return $tableHtml; // Возвращение сгенерированного HTML-кода таблицы
    }

    public function getTableRows(int $minAge): array {
        $sql = "SELECT * FROM `name` WHERE age >= ?"; // SQL-запрос с параметром для минимального возраста
        $params = array('types' => 'i', 'values' => array($minAge)); // Параметры для запроса
        $stmt = $this->executeSQL($sql, $params); // Выполнение запроса
        $result = $stmt->get_result(); // Получение результата запроса
        $rows = [];
        while ($row = $result->fetch_assoc()) { // Перебор строк результата
            $rows[] = $row;
        }
        return $rows; // Возвращение массива строк
    }

    public function deleteRecord($id): void {
        $sql = "DELETE FROM `name` WHERE id = ?"; // SQL-запрос для удаления записи по ID
        $params = array('types' => 'i', 'values' => array($id)); // Параметры для запроса

        $stmt = $this->executeSQL($sql, $params); // Выполнение запроса

        if ($stmt) { // Проверка успешности выполнения запроса
            echo " "; // Вывод пустой строки (можно удалить)
        } else {
            echo "Ошибка: " . $this->conn->error; // Вывод ошибки, если запрос не выполнен
        }
    }
}