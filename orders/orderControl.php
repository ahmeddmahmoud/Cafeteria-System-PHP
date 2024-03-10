<?php 
require("../db.php");
session_start();
$obj=new DB();
$notes=$_POST["notes"];
if(isset($_POST["roomNum"])){
  $roomNum=$_POST["roomNum"];
}else{
  $roomNum=$_SESSION['room_no'];
}
if(isset($_POST["userNameByAdmin"])){
  $userName=$_POST["userNameByAdmin"];
  $userID=$obj->getData("user","name='$userName'","id")->fetch_all(MYSQLI_ASSOC)[0]['id'];
}else{
  $userID=$_SESSION['id'];
  
}
try {
  $obj->getConnection()->begin_transaction();
  $obj->insertInto("orders","(user_id, date, status, notes, room_no)","('$userID', now(), 'processing', '$notes', '$roomNum')");
  $orderID= $obj->getData("orders","user_id=$userID and room_no=$roomNum order by id desc limit 1","id")->fetch_assoc()['id'];
  for ($i=0; $i < count($_POST['name']); $i++) { 
    $productID= $obj->getData("product","name='{$_POST['name'][$i]}'","id")->fetch_assoc()['id'];
    $obj->insertInto("orders_product","(order_id, product_id, quantity)", 
    "('{$orderID}','{$productID}','{$_POST['quantity'][$i]}')");
  }
  $obj->getConnection()->commit();
} catch (\Exception $e) {
  $obj->getConnection()->rollback();
  throw $e;
}

if(isset($_POST["userNameByAdmin"])){
  header("Location:makeOrderAdmin.php");
}else{
  header("Location:makeOrderUser.php");
}
?>
