<?php
require_once '../db.php';

try {
    $db = new DB();
} catch (Exception $e) {
    // Handle the database connection error gracefully by redirecting to the login page
    header("Location: login.php?error=Invalid_dbConnection");
    exit();
}

// Fetch user orders data from the database
$query = "SELECT u.id,
u.name,
SUM(op.quantity * p.price) AS total_price,
MAX(o.date) AS date
FROM `user` u
JOIN `orders` o ON u.id = o.user_id
JOIN `orders_product` op ON o.id = op.order_id
JOIN `product` p ON op.product_id = p.id
GROUP BY u.id, u.name;";

$userOrdersResult = $db->getConnection()->query($query);

// After fetching user orders data from the database
if ($userOrdersResult) {
    $userOrders = [];
    while ($row = $userOrdersResult->fetch_assoc()) {
        $userOrders[] = $row;
    }
    // var_dump($userOrders);
} else {
    // Handle query error if needed
    die("Error fetching user orders: " . $db->getConnection()->error);
}

// Encode user orders as JSON
$userOrdersJson = json_encode($userOrders);  // converts the array to a JSON string
?>