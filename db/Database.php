<?php

namespace db;

use config\Config;
use mysqli;
use Exception;

// Класс для работы с базой данных
class Database
{
    public ?mysqli $conn;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->conn = $this->getConnection();
    }

    // Метод для получения соединения с базой данных
    public function getConnection(): mysqli
    {
        $conn = new mysqli($this->config->getServername(), $this->config->getUsername(), $this->config->getPassword(), $this->config->getDbname());

        if ($conn->connect_error) {
            die("Ошибка подключения: " . $conn->connect_error);
        }
        return $conn;
    }

    // Метод для получения строк таблицы из базы данных

    /**
     * @throws Exception
     */
    public function getTableRows(int $minAge): array
    {
        $sql = "SELECT * FROM name WHERE age >= ?";
        $params = array('types' => 'i', 'values' => array($minAge));

        $stmt = $this->executeSQL($sql, $params);
        $result = $stmt->get_result();
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row; // Добавляем каждую строку как ассоциативный массив в $rows
        }

        return $rows; // Возвращаем данные как массив
    }


    // Метод для выполнения SQL-запросов
    public function executeSQL(string $sql, array $params = null): false|\mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($params['types'], ...$params['values']);
        }

        if (!$stmt->execute()) {
            throw new Exception("Ошибка выполнения SQL-запроса: " . $stmt->error);
        }
        return $stmt;
    }
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