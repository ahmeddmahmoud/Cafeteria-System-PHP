<?php
require '../db.php';
require './checkall.php';
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
                        <input class="form-control" type="date" id="date_from">
                    </div>
                    <div class="form-group col">
                        <label for="date_to">To:</label>
                        <input class="form-control" type="date" id="date_to">
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

                <div><button type="button" id="filter" onclick="filterOrders()" class="btn btn-primary"
                        style="height: fit-content;">Filter</button></div>

            </form>
        </div>
    </div>
    <div class="row mt-2">
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
            <div class="order-details" style="display:none;"></div>


        </div>
    </div>
</div>

<script>
// Retrieve user orders from PHP variable
const userOrders = <?php echo $userOrdersJson; ?>; //get data from json string
const ordersDate = <?php echo $ordersDateJson; ?>; //get data from json string
const orderDetails = <?php echo $detailsJson; ?>; //get data from json string
console.log(orderDetails);

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
    let filteredOrders = ordersDate.filter(order => {
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
    updateOrdersTable(filteredOrders, ordersDate, orderDetails);
}

// Function to display all orders
function displayAllOrders() {
    updateOrdersTable(userOrders, ordersDate, orderDetails);
}

// Function to update the orders table
function updateOrdersTable(orders, ordersDate, orderDetails) {

    const userOrdersTableBody = document.getElementById('userOrdersTableBody');
    userOrdersTableBody.innerHTML = ''; // Clear the existing content in the table body
    orders.forEach(order => {
        const row = `
                <tr>
                    <td>                                                 
                        <button class="show-details-btn btn btn-info" data-date="${order['date']}" data-userid="${order['id']}">+</button> ${order['name']}      
                    </td>
                    <td>${order['total_price']}
                    </td>
                </tr>
            `;
        userOrdersTableBody.insertAdjacentHTML('beforeend', row);
    });
    const showDetailsButtons = document.querySelectorAll('.show-details-btn');
    showDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-userid');
            const userdate = this.getAttribute('data-date');      //added here2 

            const orderDetailsDiv = document.querySelector('.order-details');

            // Find orders associated with the user ID
            const userOrders = ordersDate.filter(order => order['user_id'] === userId && order['date'] === userdate );   //change here*******3

            // Generate HTML to display order date and amount
            let orderDetailsHTML =
                '<table class="table" border=2><tr><th>Order Date</th><th>Amount</th></tr>';
            userOrders.forEach(order => {
                orderDetailsHTML +=
                    `<tr><td><button class="btn btn-secondary showOrder" data-userid="${order['user_id']}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td><td>${order['total_price']}</td></tr>`;
            });
            orderDetailsHTML +=
                '<tr><td colspan=2 class="text-center"><div class="contain d-flex justify-content-around align-items-center"></div></td></tr><tr><td colspan=2 class="text-center"> <button id="close" class="btn btn-danger">Close</button></td></tr></table>';

            // Update the order details div
            orderDetailsDiv.innerHTML = orderDetailsHTML;
            orderDetailsDiv.style.display = 'block';
            const closeButton = document.getElementById('close');
            closeButton.addEventListener('click', function() {
                orderDetailsDiv.style.display = 'none';
            });
            const filterButton = document.getElementById('filter');     ///i added here **********1
            filterButton.addEventListener('click', function() {
                orderDetailsDiv.style.display = 'none';
            });

            const showOrderButtons = document.querySelectorAll('.showOrder');
            showOrderButtons.forEach(showButton => {
                showButton.addEventListener('click', function() {
                    const orderUserid = this.getAttribute('data-userid');
                    const containerEle = document.querySelector('.contain');
                    const orderDate = this.getAttribute('data-orderDate');
                    const order = orderDetails.filter(order => order['user_id'] ===
                        orderUserid && order['date'] === orderDate);
                    let orderDetailsContent = '';
                    order.forEach(order => {
                        orderDetailsContent += `
                                <div>
                                <img src="../imgs/products/${order['product_image']}" style="max-width: 100px;">
                                    <p>${order['product_name']}</p>
                                    <p>Quantity: ${order['quantity']}</p>
                                    <p>Price: ${order['product_price']}</p>
                                </div>
                            `;
                        // Append the order details to the existing content
                        containerEle.innerHTML = orderDetailsContent;
                    });
                });
            });
        });
    });
}
document.addEventListener("DOMContentLoaded", function() {
    filterOrders();
});
</script>