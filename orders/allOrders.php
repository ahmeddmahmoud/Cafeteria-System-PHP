<?php
require_once '../db.php';
// session_start();
//get the page that redirected to this page
//if this page loaded with info from before
$db = new DB();
$table = 'order_details_view'; //In fact this is a view not a table
//to ve changed Waiting on AWAD
//*********************** */
// if (isset($_SESSION['login'])) {
// if (isset($_SESSION['login'])) { 
//     $name = $_SESSION['name'];
//     $user_id = $_SESSION['user_id'];
// } else {
//     //set cookie msg 
//     setcookie("msg", "You are not logged in, plz login first");
//     header("Location: ../login/login.php");
// }
// 
?>

<!-- Table -->
<?php
// $query = "SELECT * FROM UserOrdersInfo WHERE user_id = " . $_SESSION['user_id'];
$filter_condition = "user_id =1 AND DATE(order_date) BETWEEN '2024-03-01' AND '2024-03-25'"; //to be changed with variable dates
$crrentDate = date("Y-m-d");
$orders = $db->getData($table, "status != 'done'");
$productsImgsPath = "../imgs/products/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- file imports -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>My orders</title>
    <style>
        /* Styles for order */
        .order {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Styles for order info */
        .order-info {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 10px;
        }

        /* Styles for product details */
        .product-details {
            display: flex;
            flex-wrap: wrap;
        }

        /* Styles for product */
        .product {
            flex: 0 0 30%;
            /* Adjust as needed */
            padding: 10px;
            margin: 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        /* Styles for actions */
        .actions {
            margin-top: 10px;
        }
    </style>
</head>

<body class="container">
    <form class="row">
        <div class=" form-group col">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date">
        </div>
        <div class=" form-group col">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" max="<?php echo $crrentDate ?>">
        </div>
    </form>
    <button onclick="filterOrders()" class="btn btn-primary  " style="height: fit-content;">Filter</button>

    <h2 class="h2">All orders</h2>


    <?php
    $totalPrice = 0;

    foreach ($orders as $row) {
        // Splitting item quantities, prices, and images into arrays
        $quantityArr = explode(",", $row['item_quantities']);
        $priceArr = explode(",", $row['item_prices']);
        $imgArr = explode(",", $row['item_images']);
        $nextStatus = $row['status'];
        if ($nextStatus == 'processing') {
            $nextStatus = 'Deliver';
            $changeStatusButton =  "<a href='../orders/changeOrderStatus.php?id={$row['order_id']}&status={$row['status']}' class='btn btn-primary mx-auto'>$nextStatus</a>";
        } elseif ($nexStatus = 'out for delivery') {
            $nextStatus = 'Finish';
            $changeStatusButton =  "<a href='../orders/changeOrderStatus.php?id={$row['order_id']}&status={$row['status']}' class='btn btn-primary mx-auto'>$nextStatus</a>";
        }

        // Initialize the cancel button HTML
        $nexButton = '';
        if ($nextStatus == 'Deliver') {
        }
        // Output each order using heredoc syntax
        echo <<<HTML
    <div class='order'>
        <div class='order-info'>
            <p class='date'>Date: {$row['order_date']}</p>
            <p>Status: {$row['status']}</p>
            <p>Name: {$row['user_name']}</p>
            <p>Room: {$row['room_number']}</p>
            <p>Ext. No.: {$row['extension_number']}</p>
            <p>Total Amount: {$row['total_amount']}</p>
            <div class='actions'>
                <button class='show-details btn btn-primary' style='height: fit-content; margin: 10px;'>Details</button>
                $changeStatusButton
            </div>
        </div> <!-- Closing div for order-info -->

        <!-- Output product details (image, quantity, price) in a div -->
        <div class='product-details' style='display:none;'>
HTML;
        foreach ($imgArr as $key => $image) {
            $quantity = $quantityArr[$key];
            $price = $priceArr[$key];

            echo <<<HTML
            <div class='product'>
                <img src='$productsImgsPath$image' alt='Product Image' style='width: 100px; height: auto;'>
                <p>Quantity: $quantity</p>
                <p>Price: $price</p>
            </div> <!-- Closing div for product -->
HTML;
        }
        echo "</div> <!-- Closing div for product-details -->";
        echo "</div> <!-- Closing div for order -->";

        // Accumulate total price
        $totalPrice += $row['total_amount'];
    }

    ?>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Toggle product details when button is clicked
        document.querySelectorAll('.show-details').forEach(function(button) {
            button.addEventListener('click', function() {
                var order = this.closest('.order');
                var productDetails = order.querySelector('.product-details');
                var orderInfo = order.querySelector('.order-info');

                if (productDetails.style.display != 'flex') {
                    productDetails.style.display = 'flex';
                } else {
                    productDetails.style.display = 'none';
                }

            });
        });
        //remove last three letter from all .date 
        document.querySelectorAll('.date').forEach(function(date) {
            var formattedDate = date.innerText.substring(0, date.innerText.length - 3);
            date.innerText = formattedDate;
        });

        function filterOrders() {
            let allOrdersDates = document.querySelectorAll('.date');
            let startDate = document.getElementById('start_date').valueAsDate;
            let endDate = document.getElementById('end_date').valueAsDate;
            console.log(startDate, endDate);
            allOrdersDates
                .forEach(function(date) {
                    let orderDate = date.innerText.split(" ")[1];
                    orderDate = new Date(orderDate);
                    console.log(orderDate, date);
                    if (orderDate >= startDate && orderDate <= endDate) {
                        date.closest('.order').style.display = 'block';
                        console.log("found");
                    } else {
                        date.closest('.order').style.display = 'none';
                    }
                });
        }
    </script>
</body>

</html>