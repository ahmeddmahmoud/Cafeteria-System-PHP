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

// Check if session is started
//if (session_status() == PHP_SESSION_NONE) {
    session_start();
//}

// Check if user is logged in
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $name = $_SESSION['name'];
    $user_id = $_SESSION['id'];
} else {
    // Redirect to login page if user is not logged in
    setcookie("msg", "You are not logged in, please login first");
    header("Location: ../errors/err.php?err=403");
    exit(); // Stop further execution
}
?>

<style>
    .userimg {
        width: 50px;
        border-radius: 50%;
        height: 50px;
    }
    body{
    background-color: #F4EAE0 !important;
    }
    .allproduct img {
        cursor: pointer;
        margin: auto;
        display: inline-block;
    }
    .order-details > table > tbody > tr:hover{
        background-color: #F4EAE0 !important;
    }
    .order-full-details > div{
        text-align: center;
    }
    .order-full-details > div:hover{
    background-color: #FAF6F0 !important;
    z-index: 1;
    border:2px solid #000000;
    border-radius: 10px;
    }
    .order-full-details{
        margin-top: 5px;
        justify-content: space-around;
        align-items: center;
        display: none;
    }
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?PHP include "../components/nav.php" ?>
<div class="container row justify-content-between mx-auto">
    <div class=" card h-50 shadow px-4 mb-1 col-12">
        <!-- <div class="col-md-6 offset-md-3"> -->
            <h2 class="mx-auto">Checks</h2>
            <form class="my-0">
                <div class="form-row text-center align-items-center">
                    <div class="form-group col-3">
                        <label for="date_from">From:</label>
                        <input  type="date" id="date_from">
                    </div>
                    <div class="form-group col-3">
                        <label for="date_to">To:</label>
                        <input  type="date" id="date_to">
                    </div>
                    <div class="form-group col-4">
                    <label for="user">Select User:</label>
                    <select  id="user">
                        <option value="all" selected>Show All</option> <!-- Add the selected attribute -->
                        <?php foreach ($userOrders as $order): ?>    <!-- first query-->
                        <option value="<?php echo $order['id']; ?>"><?php echo $order['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="form-group col-2"><button type="button" id="filter" onclick="filterOrders()" class="btn btn-primary"
                        style="height: fit-content;">Filter</button></div>
                </div>


            </form>
        <!-- </div> -->
    </div>
    <div class=" bg-light shadow-lg col-5" style="overflow-y: auto; max-height: 420px;">
        
            <div class="text-center">
                <h2 >User Orders</h2>
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
            <div class="col-7 px-1 ">
                <div class="order-details bg-light mb-2" style="display:none;overflow-y:auto; max-height:160px;"></div>
                <div class="order-full-details bg-light" style="max-height:400px; overflow-x:auto;"></div>
            </div>

</div>

<style>
    .order-full-details{
        margin-top: 5px;
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
                                <div class="pt-2 px-2" style="display:flex; align-items:center; flex-direction:column; justify-content:center;">
                                    <img src="../imgs/products/${order['product_image']}"  style="width:60px; height:80px;border-radius:5px">
                                    <p>Quantity: ${order['quantity']}</p>
                                    <p></p>Price: ${order['product_price']} $</p>
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




<script>
/*
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

    // Create a map to store the total price for each user
    const totalPriceMap = new Map();

    // Calculate total price for each user
    orders.forEach(order => {
        const userId = order['id'];
        const totalPrice = parseFloat(order['total_price']);

        if (totalPriceMap.has(userId)) {
            totalPriceMap.set(userId, totalPriceMap.get(userId) + totalPrice);
        } else {
            totalPriceMap.set(userId, totalPrice);
        }
    });

    // Iterate through the totalPriceMap to create table rows
    totalPriceMap.forEach((totalPrice, userId) => {
        // Find the order details for the current user
        const userOrderDetails = orders.find(order => order['id'] === userId);
        // Create the row HTML
        const row = `
            <tr>
                <td>                                                 
                    <button class="show-details-btn btn btn-info" data-userid="${userId}">+</button> ${userOrderDetails['name']}      
                </td>
                <td>${totalPrice.toFixed(2)}</td>
            </tr>
        `;
        // Append the row to the table body
        userOrdersTableBody.insertAdjacentHTML('beforeend', row);
    });

    const showDetailsButtons = document.querySelectorAll('.show-details-btn');
    showDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            orderFullDetails.style.display = 'none';
            const userId = this.getAttribute('data-userid');
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
            // Find orders associated with the user ID
            let userOrders= ordersDate.filter(order => order['user_id'] ===userId);
            console.log(userOrders);
            console.log(totalPriceMap.get(userId).toFixed(2));
            let orderDetailsHTML =
                '<table class="table"><tr><th>Order Date</th><th>Amount</th></tr>';
            if(userOrders.length==1){}
            else{
                userOrders = ordersDate.filter(order => order['user_id'] ===userId && +order['total_price'] == +totalPriceMap.get(userId).toFixed(2));
                if(userOrders.length>0){}
                else{
                    userOrders = ordersDate.filter(order => order['user_id'] ===userId && +order['total_price'] < +totalPriceMap.get(userId).toFixed(2));
                }
            }
            userOrders.forEach(order => {
                orderDetailsHTML +=
                    `<tr>
                        <td><button class="btn btn-secondary showOrder" data-userid="${order['user_id']}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td>
                        <td>${order['total_price']}</td>
                    </tr>`;
                });
            orderDetailsHTML += '</table>';

            // Update the order details div
            orderDetailsDiv.innerHTML = orderDetailsHTML;
            orderDetailsDiv.style.display = 'block';
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
*/
</script>
