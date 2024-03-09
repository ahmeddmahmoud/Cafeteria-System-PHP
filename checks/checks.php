<?php
require_once '../db.php';
require_once './checkall.php';
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2>Checks</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
                <div><button type="button" onclick="filterOrders()" class="btn btn-primary" style="height: fit-content;">Filter</button></div>
                <div class="form-group">
                    <label for="user">Select User:</label>
                    <select class="form-control" id="user" name="user" onchange="this.form.submit()">
                        <option value="">Select User</option> <!-- Default option -->
                        <option value="all" <?php echo $selectedUserId === 'all' ? 'selected' : ''; ?>>Show All</option> <!-- Option to show all users -->
                        <!-- Add options dynamically from your database -->
                        <?php 
                        foreach ($userNames as $userId => $userName) {
                            $selected = $selectedUserId === $userId ? 'selected' : '';
                            echo "<option value='" . $userId . "' $selected>" . $userName . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="selected_user" id="selected_user" value="">
                <input type="hidden" name="selected_date_from" id="selected_date_from" value="">
                <input type="hidden" name="selected_date_to" id="selected_date_to" value="">
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
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($selectedUserId) || $selectedUserId === 'all') : // Check if no user selected or "Show All" option selected ?>
                        <?php foreach ($userNames as $userId => $userName): ?>
                            <?php
                                // Fetch total price for each user
                                $totalPriceResult=$db->getDataSpec("u.id,
                                u.name,
                                SUM(op.quantity * p.price) AS total_price" , " `user` u
                                JOIN
                                    `orders` o ON u.id = o.user_id
                                JOIN
                                    `orders_product` op ON o.id = op.order_id
                                JOIN
                                    `product` p ON op.product_id = p.id" , " u.id = $userId
                                    GROUP BY
                                        u.id, u.name;");

                                    if ($totalPriceResult) {
                                    $totalPrice = $totalPriceResult->fetch_assoc()['total_price'];
                                    $userTotalPrice = $totalPrice ? $totalPrice : 0;
                                }
                            ?>
                            <tr>
                                <td><?php echo $userName; ?></td>
                                <td><?php echo $userTotalPrice; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td><?php echo $userNames[$selectedUserId]; ?></td>
                            <td><?php echo $userTotalPrice; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function filterOrders() {
        // Get the selected user and date range values
        var selectedUserId = document.getElementById('user').value;
        var dateFrom = document.getElementById('date_from').value;
        var dateTo = document.getElementById('date_to').value;

        // Update the form fields with the selected values
        document.getElementById('selected_user').value = selectedUserId;
        document.getElementById('selected_date_from').value = dateFrom;
        document.getElementById('selected_date_to').value = dateTo;

        // Submit the form to trigger PHP processing
        document.querySelector('form').submit();
    }
</script>
