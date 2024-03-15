<?php
require '../db.php';
require './checkall.php';
// Check if session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (isset($_SESSION['id'])) {
    $name = $_SESSION['name'];
    $user_id = $_SESSION['id'];
} else {
    // Redirect to login page if user is not logged in
    setcookie("msg", "You are not logged in, please login first");
    header("Location: ../login/login.php");
    exit(); // Stop further execution
}
?>
<style>
    .userimg {
        width: 50px;
        border-radius: 50%;
        height: 50px;
    }

    .allproduct img {
        cursor: pointer;
        margin: auto;
        display: inline-block;
    }
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?PHP include "../components/nav.php" ?>
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
                        <?php foreach ($userOrders as $order): ?>    <!-- first query-->
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
            <div class="order-full-details"></div>
        </div>
    </div>
</div>

<style>
    .order-details{
        border: 1px solid black;
    }
    .order-full-details{
        margin-top: 5px;
        border: 1px solid black;
        justify-content: space-around;
        align-items: center;
        display: none;
    }

</style>

<script>
// Retrieve user orders from PHP variable
const userOrders = <?php echo $userOrdersJson; ?>; //get data from json string
const ordersDate = <?php echo $ordersDateJson; ?>; //get data from json string
const orderDetails = <?php echo $detailsJson; ?>; //get data from json string
const orderDetailsDiv = document.querySelector('.order-details');
const orderFullDetails = document.querySelector('.order-full-details');

function filterOrders() {
    orderDetailsDiv.style.display = 'none';
    orderFullDetails.style.display = 'none';
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
    let filteredOrders = ordersDate.filter(order => {                     //second query
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
    updateOrdersTable(ordersDate, ordersDate, orderDetails);                      //second query
}
//**************************************************************** */
// Function to update the orders table
function updateOrdersTable(orders, ordersDate, orderDetails) {

    const userOrdersTableBody = document.getElementById('userOrdersTableBody');
    userOrdersTableBody.innerHTML = ''; // Clear the existing content in the table body
    
    // Create an object to store total prices for each user
    const userTotalPrices = {};
    
    // Iterate over the orders to group them by user ID and sum the total prices
    orders.forEach(order => {
        const userId = order['id'];
        const totalPrice = parseFloat(order['total_price']);
        
        if (userId in userTotalPrices) {
            // If the user ID already exists in the object, add the total price to it
            userTotalPrices[userId] += totalPrice;
        } else {
            // If the user ID doesn't exist, initialize it with the total price
            userTotalPrices[userId] = totalPrice;
        }
    });
    
    // Iterate over the userTotalPrices object to create rows for each user
    for (const userId in userTotalPrices) {
        const userName = orders.find(order => order['id'] === userId)['name'];
        const totalPrice = userTotalPrices[userId].toFixed(2);
        const userOrder = orders.find(order => order['id'] === userId); // Find the user's order
        const date = userOrder['date'];    
        // console.log(date);
        const row = `
            <tr>
                <td>                                                 
                    <button class="show-details-btn btn btn-info" data-date=${date} data-userid="${userId}">+</button> ${userName}      
                </td>
                <td>${totalPrice}</td>
            </tr>
        `;
        userOrdersTableBody.insertAdjacentHTML('beforeend', row);
    }

//**************************************************/ */

const showDetailsButtons = document.querySelectorAll('.show-details-btn');
showDetailsButtons.forEach(button => {
    button.addEventListener('click', function() {
        orderFullDetails.style.display = 'none';
        const userId = this.getAttribute('data-userid');
        const userdate = this.getAttribute('data-date');

        if (this.innerText === '-') {
            orderDetailsDiv.style.display = 'none';
            this.innerText = '+';
            return; 
        }
        // Reset all buttons to show '+'
        showDetailsButtons.forEach(btn => {
            btn.innerText = '+';
        });
        // Set the clicked button's text to '-'
        this.innerText = '-';
        // Retrieve the selected date range
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        // Convert date strings to Date objects
        const fromDate = new Date(dateFrom);
        const toDate = new Date(dateTo);
        // console.log(fromDate);
        // console.log(toDate);

        // Find orders associated with the user ID and within the date range
        const userOrders = ordersDate.filter(order => {                               //check if date range selected
        // Check if isDateInRange function exists and is a function
        if (dateFrom && dateTo ) {
            return order['user_id'] === userId && isDateInRange(order['date'], fromDate, toDate);
        } else {
            return order['user_id'] === userId ;
        }
});
        // Generate HTML to display order date and amount
        let orderDetailsHTML = '<table class="table"><tr><th>Order Date</th><th>Amount</th></tr>';
        userOrders.forEach(order => {
            orderDetailsHTML += `<tr>
                                    <td><button class="btn btn-secondary showOrder" data-userid="${order['user_id']}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td>
                                    <td>${order['total_price']}</td>
                                </tr>`;
        });
        orderDetailsHTML += '</table>';

        // Update the order details div
        orderDetailsDiv.innerHTML = orderDetailsHTML;
        orderDetailsDiv.style.display = 'block';

        // Function to check if a date falls within a specified range
        function isDateInRange(date, fromDate, toDate) {
            const orderDate = new Date(date);
            return orderDate >= fromDate && orderDate <= toDate;
        }

            const showOrderButtons = document.querySelectorAll('.showOrder');
            showOrderButtons.forEach(showButton => {
                showButton.addEventListener('click', function() {
                    const orderUserid = this.getAttribute('data-userid');

                    const orderDate = this.getAttribute('data-orderDate');
                    const order = orderDetails.filter(order => order['user_id'] === orderUserid && order['date'] === orderDate);
                    if (this.innerText === '-') {
                        orderFullDetails.style.display = 'none';
                        this.innerText = '+';
                        return; 
                    }


                    let orderDetailsContent = '';
                    order.forEach(order => {
                        orderDetailsContent += `
                                <div class="pt-2">
                                <img src="../imgs/products/${order['product_image']}" style="max-width: 100px;">
                                    <p>${order['product_name']}</p>
                                    <p>Quantity: ${order['quantity']}</p>
                                    <p>Price: ${order['product_price']}</p>
                                </div>
                            `;
                        // Append the order details to the existing content
                        orderFullDetails.innerHTML = orderDetailsContent;
                    });
                    orderFullDetails.style.display = 'flex';
                    // Reset all buttons to show '+'
                    showOrderButtons.forEach(btn => {
                    btn.innerText = '+';
                    });
                    // Set the clicked button's text to '-'
                    this.innerText = '-';

                });
            });
        });
    });
}
document.addEventListener("DOMContentLoaded", function() {
    filterOrders();
});
</script>