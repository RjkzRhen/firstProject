<?php
namespace forms;

use data\CSVEditor;

class CSVInsertForm {
    private $filePath;
    private $insertForm;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fields = $this->insertForm->getDataFromFormAndUpdateTemplate();
            if ($this->isAllValid($fields)) {
                $this->insertForm->insertIntoCSV($fields, $this->csvEditor);
                header("Location: /csv");
                exit;
            }
        }
    }


}