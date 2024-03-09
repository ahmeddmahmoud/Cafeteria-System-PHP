<?php

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$Room_No = $_POST['room_no'];
$Ext = $_POST['ext'];

$image=$_FILES['image'];
$imgName=$_FILES['image']['name'];
//$imageName = $_POST['image'];


require "../db.php";
$db = new DB();
$db->__construct();
session_start();
$oldRoom = $_SESSION['roomNo'];
move_uploaded_file($image["tmp_name"], "../imgs/users/".$imageName);

try {
     $db->update_data("rooms" , "room_no = '$Room_No' , ext = '$Ext'" , "room_no = '$oldRoom'");
     $db->update_data("user" , "name = '$name' , email = '$email' , password = '$password' , room_no = '$Room_No' , image = '$imageName'", "id = '$id'");

}catch(Exception $e){
    var_dump($e -> getMessage());
}


?>