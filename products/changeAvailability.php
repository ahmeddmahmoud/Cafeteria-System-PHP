<?php
include_once '../db.php'; // Include the DB class file
$db = new DB();
$db->__construct();
try{
    if (isset($_GET['id']) && isset($_GET['status'])) {
    $productId = $_GET['id'];
    $currentStatus = $_GET['status'];

    // Determine the new availability status
    $newStatus = ($currentStatus == 1) ? 0 : 1; // Toggle between 1 and 2

    // Update the availability status in the database
    $db = new DB();
    $db->__construct();
    $db->updateData("product", "available = '$newStatus'", "id = '$productId'");

    // Redirect back to the products page
    header("location: productTable.php?page=" . $_GET['page']);
    exit();
}
}catch(Exception $e){
    echo $e->getMessage();
}

?>
