<?php
require_once '../db.php';
// session_start();
//get the page that redirected to this page
//if this page loaded with info from before
$db = new DB();
$table = 'UserOrdersInfo'; //In fact this is a view not a table
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
$orders = $db->getData($table);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- file imports -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>My orders</title>
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
    <button onclick="filterTable()" class="btn btn-primary  " style="height: fit-content;">Filter</button>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalPrice = 0;
            foreach ($orders as $row) {
                echo "<tr>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['order_status'] . "</td>";
                echo "<td>" . $row['total_amount'] . "</td>";
                $totalPrice += $row['total_amount']; //to accumulate the total cost for the user 
                //to show the cancel button only if the order is in processing status
                if ($row['order_status'] == 'processing') {
                    echo "<td>
                        <a href='../orders/deleteOrder.php?id=" . $row['order_id'] . "' class='btn btn-danger'>Cancel</a>
                    </td>";
                } else {
                    echo "<td></td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td colspan="3"><?php echo $totalPrice; ?></td>
            </tr>
        </tfoot>
    </table>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        //get every first cell content in tbody 

        var cells = document.querySelectorAll("tbody tr td:first-child");
        //split inner text on space and change the value of the cell to the first element 
        cells.forEach(function(cell) {
            var text = cell.innerText.split(" ");
            cell.innerText = text[0];
        });

        function filterTable() {
            let startDate = document.getElementById("start_date").valueAsDate;
            let endDate = document.getElementById("end_date").valueAsDate;
            // show tr only with cells between startDate and endDate
            document.querySelectorAll("tbody tr").forEach(function(row) {
                var date = new Date(row.cells[0].innerText);
                if (date >= startDate && date <= endDate) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            })
        }
    </script>
</body>

</html>