<?php
require_once '../db.php';
// session_start();
//get the page that redirected to this page
// $page = $_SERVER['HTTP_REFERER'];
//if this page loaded with info from before
// if ($page == 'filterOrders.php') {
// }
$db = new Database();
$table = 'UserOrdersInfo'; //In fact this is a view not a table
//to ve changed Waiting on AWAD
//*********************** */
if (isset($_SESSION['login'])) {
    $name = $_SESSION['name'];
    $user_id = $_SESSION['user_id'];
} else {
    //set cookie msg 
    setcookie("msg", "You are not logged in, plz login first");
    header("Location: ../login/login.php");
}
?>

<!-- Table -->
<?php
// $query = "SELECT * FROM UserOrdersInfo WHERE user_id = " . $_SESSION['user_id'];
$filter_condition = "AND DATE(order_date) BETWEEN '2024-03-01' AND '2024-03-05'"; //to be changed with variable dates

$orders = $db->readRecord($table, "user_id =1 $filter_condition");
?>
<form method="GET" action="">
    <label for="start_date">start Date:</label>
    <input type="date" id="start_date" name="start_date">
    <label for="end_date">end Date:</label>
    <input type="date" id="end_date" name="end_date">
    <input type="submit" value="Filter">
</form>
<table border="2">
    <thead>
        <td>Date</td>
        <td>Status</td>
        <td>Amount</td>
        <td>Action</td>
    </thead>
    <tbody>
        <?php
        $totalPrice = 0;
        foreach ($orders as $row) {
            echo "<tr>";
            echo "<td>" . $row['order_date'] . "</td>";
            echo "<td>" . $row['order_status'] . "</td>";
            echo "<td>" . $row['total_amount'] . "</td>";
            $totalPrice += $row['total_amount']; //to accumelate the total cost for the user 
            //to show the cancel button only if the order is in processing status
            if ($row['order_status'] == 'processing')
                echo "<td>
                        <a href='../order/deleteOrder.php?id=" . $row['order_id'] . "'>Cancel</a>
                    </td>";
            else
                echo "<td></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
    <tfoot>
        <td>Total</td>
        <td colspan="3"> <?php echo $totalPrice; ?></td>
    </tfoot>
</table>