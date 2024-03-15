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
 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
            if(userOrders.length==1){
                userOrders.forEach(order => {
                    orderDetailsHTML +=
                        `<tr>
                            <td><button class="btn btn-secondary showOrder" data-userid="${order['user_id']}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td>
                            <td>${order['total_price']}</td>
                        </tr>`;
                });
            }else{
                userOrders = ordersDate.filter(order => order['user_id'] ===userId && +order['total_price'] == +totalPriceMap.get(userId).toFixed(2));
                if(userOrders){
                    userOrders.forEach(order => {
                    orderDetailsHTML +=
                        `<tr>
                            <td><button class="btn btn-secondary showOrder" data-userid="${order['user_id']}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td>
                            <td>${order['total_price']}</td>
                        </tr>`;
                });
                }else{
                    userOrders = ordersDate.filter(order => order['user_id'] ===userId && +order['total_price'] < +totalPriceMap.get(userId).toFixed(2));
                    userOrders.forEach(order => {
                    orderDetailsHTML +=
                        `<tr>
                            <td><button class="btn btn-secondary showOrder" data-userid="${order['user_id']}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td>
                            <td>${order['total_price']}</td>
                        </tr>`;
                })
                }
                console.log(totalPriceMap.get(userId).toFixed(2));
            }
            orderDetailsHTML += '</table>';
            //&& order['total_price']==totalPriceMap.get(userId).toFixed(2)



            // Generate HTML to display order date and amount


            // totalPriceMap.forEach((totalPrice, userId) => {
            // // Retrieve orders associated with the current user ID from the map
            // const userOrders = ordersDate.filter(order => order['user_id'] === userId);

            // // Iterate over userOrders and add rows to orderDetailsHTML
            // userOrders.forEach(order => {
            //     orderDetailsHTML += `
            //         <tr>
            //             <td><button class="btn btn-secondary showOrder" data-userid="${userId}" data-orderDate="${order['date']}" >+</button> ${order['date']}</td>
            //             <td>${order['total_price']}</td>
            //         </tr>`;
            // });
            // });
            

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
</script>