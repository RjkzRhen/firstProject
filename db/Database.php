<?php

namespace db; // Определяем пространство имен для класса

use config\Config; // Импортируем класс Config из пространства имен config
use mysqli; // Импортируем класс mysqli для работы с базой данных
use Exception; // Импортируем класс Exception для обработки исключений

// Класс для работы с базой данных
class Database
{
    public ?mysqli $conn; // Свойство для хранения соединения с базой данных
    private Config $config; // Свойство для хранения объекта конфигурации

    public function __construct(Config $config) // Конструктор класса, принимает объект конфигурации
    {
        $this->config = $config; // Присваиваем объект конфигурации свойству класса
        $this->conn = $this->getConnection(); // Получаем соединение с базой данных
    }

    // Метод для получения соединения с базой данных
    public function getConnection(): mysqli
    {
        $conn = new mysqli($this->config->getServername(), $this->config->getUsername(), $this->config->getPassword(), $this->config->getDbname()); // Создаем соединение с базой данных

        if ($conn->connect_error) { // Проверяем ошибку подключения
            die("Ошибка подключения: " . $conn->connect_error); // Завершаем выполнение скрипта в случае ошибки
        }
        return $conn; // Возвращаем соединение с базой данных
    }

    // Метод для получения строк таблицы из базы данных

    /**
     * @throws Exception
     */
    public function getTableRows(int $minAge): array
    {
        $sql = "SELECT * FROM name WHERE age >= ?"; // SQL-запрос для выборки строк с возрастом больше или равным minAge
        $params = array('types' => 'i', 'values' => array($minAge)); // Параметры для подготовленного запроса

        $stmt = $this->executeSQL($sql, $params); // Выполняем SQL-запрос
        $result = $stmt->get_result(); // Получаем результат выполнения запроса
        $rows = []; // Инициализируем массив для хранения строк

        while ($row = $result->fetch_assoc()) { // Перебираем строки результата
            $rows[] = $row; // Добавляем каждую строку как ассоциативный массив в $rows
        }

        return $rows; // Возвращаем массив строк
    }

    // Метод для выполнения SQL-запросов
    public function executeSQL(string $sql, array $params = null): false|\mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql); // Подготавливаем SQL-запрос
        if ($params) { // Проверяем наличие параметров
            $stmt->bind_param($params['types'], ...$params['values']); // Привязываем параметры к запросу
        }

        if (!$stmt->execute()) { // Выполняем запрос и проверяем на ошибки
            throw new Exception("Ошибка выполнения SQL-запроса: " . $stmt->error); // Выбрасываем исключение в случае ошибки
        }
        return $stmt; // Возвращаем объект подготовленного запроса
    }
    public function deleteRecord($id): void
    {
        $sql = "DELETE FROM `name` WHERE id = ?"; // SQL-запрос для удаления записи по ID
        $params = array('types' => 'i', 'values' => array($id)); // Параметры для подготовленного запроса

        $stmt = $this->executeSQL($sql, $params); // Выполняем SQL-запрос

        if (!$stmt) { // Проверяем успешность выполнения запроса
            echo "Ошибка: " . $this->conn->error; // Выводим ошибку, если запрос не выполнен
        }
    }
}