<?php
require_once '../db.php';

try {
    $db = new DB();
} catch (Exception $e) {
    // Handle the database connection error gracefully by redirecting to the login page
    header("Location: login.php?error=Invalid_dbConnection");
    exit();
}

// Retrieve user data from the database
$usersResult = $db->getConnection()->query("SELECT id, name FROM user");
if (!$usersResult) {
    die("Error fetching users: " . $db->getConnection()->error);
}

// Fetch all user names and store them in an array for later use
$userNames = [];
while ($user = $usersResult->fetch_assoc()) {
    $userNames[$user['id']] = $user['name'];
}

// Create an associative array to store the total amount of orders for each user
$userOrders = [];

// Fetch the total amount of orders for each user
foreach ($userNames as $userId => $userName) {
    $totalOrdersResult = $db->getConnection()->query("SELECT COUNT(*) as total_orders FROM orders WHERE user_id = $userId");
    if ($totalOrdersResult) {
        $totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'];
        $userOrders[$userId] = $totalOrders;
    } else {
        $userOrders[$userId] = 0; // Default to 0 if no orders found
    }
}
?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Checks</h2>
                <form action="" method="POST">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="date_from">From:</label>
                            <input class="form-control" type="date" id="date_from" name="date_from" required>
                        </div>
                        <div class="form-group col">
                            <label for="date_to">To:</label>
                            <input class="form-control" type="date" id="date_to" name="date_to" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user">Select User:</label>
                        <select class="form-control" id="user" name="user">
                            <?php 
                            foreach ($userNames as $userId => $userName) {
                                echo "<option value='" . $userId . "'>" . $userName . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <h2>User Orders</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Total Orders</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userOrders as $userId => $totalOrders): ?>
                            <tr>
                                <td><?php echo $userNames[$userId]; ?></td>
                                <td><?php echo $totalOrders; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
