
<?php
require_once '../db.php';
if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = $_GET['id'];
    $status = $_GET['status'];
    $db = new DB();
    if ($status == "processing") {
        $status = "out for delivery";
    } else if ($status == "out for delivery") {
        $status = "done";
    }

    try {
        $db->updateData('orders', "status = '$status'",  "id = $id");
        header('location: ./allOrders.php');
    } catch (Exception $e) {
        setcookie('message', $e->getMessage(), time() + 300);
        header('location: ./allOrders.php');
    }
}
?>