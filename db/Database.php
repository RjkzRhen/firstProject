<?php
namespace db;

use config\Config;
use mysqli;
use mysqli_stmt;
use Exception;

// Класс для работы с базой данных
class Database
{
    public ?mysqli $conn; // Свойство для хранения соединения с базой данных
    private Config $config; // Свойство для хранения объекта конфигурации

    // Конструктор класса
    public function __construct(Config $config)
    {
        $this->config = $config; // Присваивание переданного объекта конфигурации свойству $config
        $this->conn = $this->getConnection(); // Получение соединения с базой данных и присваивание его свойству $conn
    }

    // Метод для получения соединения с базой данных
    private function getConnection()
    {
        $conn = new mysqli($this->config->getServername(), $this->config->getUsername(), $this->config->getPassword(), $this->config->getDbname()); // Создание соединения с базой данных

        if ($conn->connect_error) { // Проверка на ошибку соединения
            die("Ошибка подключения: " . $conn->connect_error); // Вывод ошибки и завершение скрипта
        }
        return $conn; // Возвращение соединения
    }

    // Метод для выполнения SQL-запросов

    /**
     * @throws Exception
     */
    public function executeSQL($sql, $params = null): false|mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql); // Подготовка SQL-запроса
        if ($params) { // Если переданы параметры
            $stmt->bind_param($params['types'], ...$params['values']); // Привязка параметров к запросу
        }
        if (!$stmt->execute()) { // Выполнение запроса
            throw new Exception("Ошибка выполнения SQL-запроса: " . $stmt->error); // Выброс исключения в случае ошибки
        }
        return $stmt; // Возвращение объекта подготовленного запроса
    }

    // Метод для получения строк таблицы из базы данных
    // Используется в методе loadData класса Table
    /**
     * @param int $minAge
     * @return string
     * @throws Exception
     */
    public function getTableRows(int $minAge): string
    {
        $sql = "SELECT * FROM `name` WHERE age >= ?"; // SQL-запрос с параметром для минимального возраста
        $params = array('types' => 'i', 'values' => array($minAge)); // Параметры для запроса
        $stmt = $this->executeSQL($sql, $params); // Выполнение запроса
        $result = $stmt->get_result(); // Получение результата запроса
        $rows = [];
        while ($row = $result->fetch_assoc()) { // Перебор строк результата
            $rows[] = $row; // Добавление строки в массив
        }

        $tableHtml = "";
        foreach ($rows as $row) { // Перебор строк таблицы
            $ageClass = $row['age'] > 50 ? 'age-over-50' : ''; // Определение класса для возраста
            $tableHtml .= "<tr>\n";
            $tableHtml .= "<td>{$row['id']}</td>\n"; // Добавление ячейки с ID
            $tableHtml .= "<td>{$row['last_name']}</td>\n"; // Добавление ячейки с фамилией
            $tableHtml .= "<td>{$row['first_name']}</td>\n"; // Добавление ячейки с именем
            $tableHtml .= "<td>{$row['middle_name']}</td>\n"; // Добавление ячейки с отчеством
            $tableHtml .= "<td class='{$ageClass}'>{$row['age']}</td>\n"; // Добавление ячейки с возрастом
            $tableHtml .= "<td><a href='?deleteId={$row['id']}'>Удалить</a></td>\n"; // Добавление ссылки для удаления
            $tableHtml .= "</tr>\n";
        }
        return $tableHtml; // Возвращение сгенерированного HTML-кода таблицы
    }

    // Метод для удаления записи по ID
    public function deleteRecord($id): void
    {
        $sql = "DELETE FROM `name` WHERE id = ?"; // SQL-запрос для удаления записи по ID
        $params = array('types' => 'i', 'values' => array($id)); // Параметры для запроса

        $stmt = $this->executeSQL($sql, $params); // Выполнение запроса

        if (!$stmt) { // Проверка успешности выполнения запроса
            echo "Ошибка: " . $this->conn->error; // Вывод ошибки, если запрос не выполнен
        }
    }
}