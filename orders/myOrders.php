<?php
try {
    require_once '../db.php';

    $db = new DB();
    $table = 'order_details_view';

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

    // Retrieve orders based on user ID and date range
    $orders = $db->getData($table, "user_id = '$user_id'");
} catch (Exception $e) {
    // Handle exceptions
    echo "Error: " . $e->getMessage();
}
?>

<!-- Table -->
<?php
// $query = "SELECT * FROM UserOrdersInfo WHERE user_id = " . $_SESSION['user_id'];
$filter_condition = "user_id =1 AND DATE(order_date) BETWEEN '2024-03-01' AND '2024-03-25'"; //to be changed with variable dates
$crrentDate = date("Y-m-d");
// $orders = $db->getData($table);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- file imports -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>My orders</title>
    <link rel="stylesheet" href="../style/nav.css">
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

        .red-border {
            border: 2px solid red;
        }
    </style>
</head>

<body class="">
    <?PHP include "../components/nav.php" ?>
    <main class="container">
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

        <button onclick="filterOrders()" class="btn btn-primary my-2 " style="height: fit-content;">Filter</button>

        <h2 class="h2 my-5">My Orders</h2>


        <?php
        $totalPrice = 0;

        foreach ($orders as $row) {
            // Splitting item quantities, prices, and images into arrays
            $quantityArr = explode(",", $row['item_quantities']);
            $priceArr = explode(",", $row['item_prices']);
            $imgArr = explode(",", $row['item_images']);

            // Initialize the cancel button HTML
            $cancelButton = ($row['status'] == 'processing') ? "<a href='../orders/deleteOrder.php?id={$row['order_id']}' class='btn btn-danger mx-auto'>Cancel</a>" : '';

            // Output each order using heredoc syntax
            echo <<<HTML
    <div class='order'>
        <div class='order-info'>
            <p class='date'>Date: {$row['order_date']}</p>
            <p>Status: {$row['status']}</p>
            <p>Total Amount: {$row['total_amount']}</p>
            <div class='actions'>
                $cancelButton
                <button class='show-details btn btn-primary' style='height: fit-content; margin: 10px;'>Details</button>
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
                <img src='../imgs/products/$image' alt='Product Image' style='width: 100px; height: auto;'>
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

        echo "<hr>";
        echo "<div class='total-container order-info'>";
        echo "<h3>Total</h3>";
        echo "<div class='total-price'>$totalPrice</div>";
        echo "</div>";
        ?>




    </main>
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
                        // date.closest('.order').classList.toggle('red-border');
                        console.log("found");
                    } else {
                        date.closest('.order').style.display = 'none';
                        if (date.closest('.order').classList.contains('red-border')) {
                            // date.closest('.order').classList.toggle('red-border');
                        }
                    }
                });
        }
        //give the neareset elemnt that have order class a red border on clicking button that have show details class 

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('show-details')) {
                let order = e.target.closest('.order');
                let allOrders = document.querySelectorAll('.order');
                //close other orders
                allOrders.forEach(function(order) {
                    if (order.classList.contains('red-border')) {
                        order.classList.toggle('red-border');
                        order.closest('.product-details').display = 'none';

                    }
                });
                order.classList.toggle('red-border');
                order.closest('.product-details').display = 'block';
            }
        });
    </script>
</body>

</html>