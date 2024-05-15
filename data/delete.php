<?php
include_once '../db/db.php';
function deleteRecordById($id) {
    deleteRecord($id);
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteRecordById($id);
}
?>