<?php
require_once '../db.php';
$id;
$table = 'orders';
//check if id is set and is number
//TODO: add checkLogin() 
//checkLogin(); 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $db = new DB();
    $db->delete($table, "id = '$id'");
    // return to the caller page and set a messege in cookie
    setcookie("msg", "Order canceled successfully");
    header("Location: $_SERVER[HTTP_REFERER]");
}
