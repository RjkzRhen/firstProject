<?php
namespace formsCSV; // Определяет пространство имен для класса AddRecord.

require_once 'CSVWriter.php'; // Подключение файла CSVWriter.php, содержащего определение класса CSVWriter.
use PageInterface; // Импорт интерфейса PageInterface для реализации в классе AddRecord.

class AddRecord implements PageInterface { // Объявление класса AddRecord, который реализует интерфейс PageInterface.
    private CSVWriter $csvWriter; // Приватное свойство для хранения экземпляра класса CSVWriter.

    public function __construct($filePath) { // Конструктор класса, принимает путь к файлу CSV.
        $this->csvWriter = new CSVWriter($filePath); // Создание нового объекта CSVWriter и сохранение его в свойстве класса.
    }

    public function handlePost(): array { // Метод для обработки POST запроса и добавления записи в CSV файл.
        $fields = [ // Массив полей формы, содержащий имя, значение и признак валидности.
            ['name' => 'username', 'value' => '', 'isValid' => true],
            ['name' => 'lastname', 'value' => '', 'isValid' => true],
            ['name' => 'firstname', 'value' => '', 'isValid' => true],
            ['name' => 'middlename', 'value' => '', 'isValid' => true],
            ['name' => 'age', 'value' => '', 'isValid' => true],
        ];

        $allValid = true;  // Флаг, проверяющий валидность всех полей.

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверка, что текущий запрос является POST.
            foreach ($fields as &$field) { // Перебор всех полей формы.
                $fieldValue = $_POST[$field['name']] ?? ''; // Получение значения поля из POST данных.
                if (empty($fieldValue)) { // Проверка, если поле пустое.
                    $field['isValid'] = false; // Установка признака невалидности поля.
                    $allValid = false;  // Установка общего флага невалидности.
                } else {
                    $field['value'] = $fieldValue;  // Сохранение значения поля.
                }
            }

            if ($allValid) { // Проверка, если все поля валидны.
                // Подготовка данных для записи в CSV.
                $data = array_map(function ($field) {
                    return $field['value'];
                }, $fields);

                // Попытка записи в CSV файл.
                try {
                    $this->csvWriter->addRecord($data);
                    // Перенаправление для избежания повторной отправки формы.
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                } catch (\Exception $e) { // Обработка возможных исключений.
                    echo "Error: " . $e->getMessage();
                }
            }
        }

        return $fields; // Возврат массива полей формы.
    }


    public function getHtml(): string {
        $fields = $this->handlePost();
        $html = '<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8">
    <title>Добавление пользователя в CSV</title>
    <style type="text/css">
        .error { border: 2px solid #ff0000; }
        .req:valid { border: 2px solid #000000; }
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); width: 400px; }
        label { font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        input.error { border: 2px solid #ff0000; } 
        input.req { outline: none; }
        input[type="submit"] { background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <form method="post">';

        foreach ($fields as $field) { // Перебор полей для создания элементов формы.
            $class = $field['isValid'] ? "req" : "error";  // Применение класса в зависимости от валидности.
            $html .= '<label for="' . $field['name'] . '">' . ucfirst($field['name']) . ':</label>';
            $html .= '<input type="text" name="' . $field['name'] . '" value="' . htmlspecialchars($field['value']) . '" class="' . $class . '"><br>';
        }

        $html .= '<input type="submit" value="Add Record">
    </form>
</body>
</html>';

        return $html; // Возвращение сгенерированного HTML кода.
    }
}