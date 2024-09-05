<?php
namespace formsCSV;

require_once 'CSVWriter.php';
use PageInterface;

class AddRecord implements PageInterface {
    private CSVWriter $csvWriter;

    public function __construct($filePath) {
        $this->csvWriter = new CSVWriter($filePath);
    }

    public function handlePost(): array {
        $fields = [
            ['name' => 'username', 'value' => '', 'isValid' => true],
            ['name' => 'lastname', 'value' => '', 'isValid' => true],
            ['name' => 'firstname', 'value' => '', 'isValid' => true],
            ['name' => 'middlename', 'value' => '', 'isValid' => true],
            ['name' => 'age', 'value' => '', 'isValid' => true],
        ];

        $allValid = true;  // Flag to check if all fields are valid

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($fields as &$field) {
                $fieldValue = $_POST[$field['name']] ?? '';
                if (empty($fieldValue)) {
                    $field['isValid'] = false;
                    $allValid = false;  // Set flag to false if any field is invalid
                } else {
                    $field['value'] = $fieldValue;  // Store the value from POST data
                }
            }

            if ($allValid) {
                // Prepare data for CSV writing
                $data = array_map(function ($field) {
                    return $field['value'];
                }, $fields);

                // Write to CSV
                try {
                    $this->csvWriter->addRecord($data);
                    // Redirect to avoid form resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                } catch (\Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }

        return $fields;
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

        foreach ($fields as $field) {
            $class = $field['isValid'] ? "req" : "error";  // Apply class based on validation
            $html .= '<label for="' . $field['name'] . '">' . ucfirst($field['name']) . ':</label>';
            $html .= '<input type="text" name="' . $field['name'] . '" value="' . htmlspecialchars($field['value']) . '" class="' . $class . '"><br>';
        }

        $html .= '<input type="submit" value="Add Record">
    </form>
</body>
</html>';

        return $html;
    }
}