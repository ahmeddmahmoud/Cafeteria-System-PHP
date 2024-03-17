<?php
include_once '../db.php'; // Include the DB class file
$db = new DB();
require_once '../functions/validateSourcePage.php';
if (!isset($_POST['id'])) {
    validateSourcePage('productTable.php', '../errors/err.php', 403);
}
//query
$_id = $_GET['id'];
try {
    $db->delete("product", "id=$_id");
} catch (Exception $e) {
    var_dump($e->getMessage());
}
header("location: productTable.php?page=" . $_GET['page']);
