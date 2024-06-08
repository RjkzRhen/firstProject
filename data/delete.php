<?php
include_once '../db/Database.php';

class DeletePage {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function deleteRecordById($id) {
        $this->db->deleteRecord($id);
        header("Location: index.php");
        exit;
    }

    public function handleRequest() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->deleteRecordById($id);
        }
    }
}

$page = new DeletePage();
$page->handleRequest();
?>