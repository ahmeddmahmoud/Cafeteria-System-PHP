<?php
require_once '../db.php';
require_once './checkall.php';
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2>Checks</h2>
            <form>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="date_from">From:</label>
                        <input class="form-control" type="date" id="date_from" >
                    </div>
                    <div class="form-group col">
                        <label for="date_to">To:</label>
                        <input class="form-control" type="date" id="date_to" >
                    </div>
                    </div>
                    <div class="form-group">
                            <label for="user">Select User:</label>
                            <select class="form-control" id="user">
                                <option value="all" selected>Show All</option> <!-- Add the selected attribute -->
                                    <?php foreach ($userOrders as $order): ?>
                                <option value="<?php echo $order['id']; ?>"><?php echo $order['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>

                <div><button type="button" onclick="filterOrders()" class="btn btn-primary" style="height: fit-content;">Filter</button></div>

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
                <tbody id="userOrdersTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Retrieve user orders from PHP variable
    const userOrders = <?php echo $userOrdersJson; ?>;  //get data from json string

    function filterOrders() {
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        const selectedUserId = document.getElementById('user').value;

        // Check if the user selected any filtering criteria
        if (!dateFrom && !dateTo && selectedUserId === 'all') {
            // If 'Show All' option selected, display all orders
            displayAllOrders();
            return;
        }

        // Filter user orders based on selected user and date range
        let filteredOrders = userOrders.filter(order => {
            const orderDate = new Date(order['date']);
            const fromDate = new Date(dateFrom);
            const toDate = new Date(dateTo);

            // Check if the order matches the selected user
            const userIdMatch = selectedUserId === '' || selectedUserId === 'all' || order['id'] === selectedUserId;

            // Check if the order falls within the selected date range
            const dateRangeMatch = (!dateFrom || !dateTo) || (orderDate >= fromDate && orderDate <= toDate);

            return userIdMatch && dateRangeMatch;
        });

        // Update table with filtered user orders
        updateOrdersTable(filteredOrders);
    }

    // Function to display all orders
    function displayAllOrders() {
        updateOrdersTable(userOrders);
    }

    // Function to update the orders table
    function updateOrdersTable(orders) {
        const userOrdersTableBody = document.getElementById('userOrdersTableBody');
        userOrdersTableBody.innerHTML = '';    // Clear the existing content in the table body

        orders.forEach(order => {
            const row = `
                <tr>
                    <td>${order['name']}</td>
                    <td>${order['total_price']}</td>
                </tr>
            `;
            userOrdersTableBody.insertAdjacentHTML('beforeend', row);
        });
    }
</script>
