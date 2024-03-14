<?php
include_once '../db.php'; // Include the DB class file
$db=new DB();
//query
$_id=$_GET['id'];
try {
    $db->delete("product","id=$_id");
}catch (Exception $e) {
    var_dump($e->getMessage());
}
header("location: productTable.php");
?>