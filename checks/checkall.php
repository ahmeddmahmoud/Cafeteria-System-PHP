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

// Fetch user orders based on date
$query2= " SELECT
u.id,
u.name,
o.user_id,
o.date,
SUM(p.price * op.quantity) AS total_price
FROM `user` u 
JOIN `orders` o ON u.id = o.user_id 
JOIN orders_product op ON o.id = op.order_id 
JOIN product p ON op.product_id = p.id 
GROUP BY o.user_id, o.date 
ORDER BY o.user_id, o.date;";
$userOrdersDate = $db->getConnection()->query($query2);

// Fetch order details based on date
$query3="SELECT
o.user_id,
o.date,
p.name AS product_name,
p.price AS product_price,
p.image AS product_image,
op.quantity
FROM
orders o
JOIN
orders_product op ON o.id = op.order_id
JOIN
product p ON op.product_id = p.id
ORDER BY
o.date;
";
$orderDetails = $db->getConnection()->query($query3);

// After fetching user orders data from the database
if ($userOrdersResult) {
    $userOrders = [];
    while ($row = $userOrdersResult->fetch_assoc()) {
        $userOrders[] = $row;
    }
} else {
    // Handle query error if needed
    die("Error fetching user orders: " . $db->getConnection()->error);
}

// After fetching user orders data from the database
if ($userOrdersDate) {
    $OrdersDate = [];
    while ($row = $userOrdersDate->fetch_assoc()) {
        $OrdersDate[] = $row;
    }
} else {
    // Handle query error if needed
    die("Error fetching Data: " . $db->getConnection()->error);
}

// After fetching user orders data from the database
if ($orderDetails) {
    $details = [];
    while ($row = $orderDetails->fetch_assoc()) {
        $details[] = $row;
    }
} else {
    // Handle query error if needed
    die("Error fetching Data: " . $db->getConnection()->error);
}

// Encode user orders as JSON
$userOrdersJson = json_encode($userOrders);  // converts the array to a JSON string
$ordersDateJson = json_encode($OrdersDate);  // converts the array to a JSON string
$detailsJson = json_encode($details);  // converts the array to a JSON string
?>