<?php

function validateData($data) {
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$connection = new mysqli("localhost", "root", "12345m", "cafe_Db");
if ($connection->connect_error) {
    die("connection failed");

}
$name = $_POST['name']; 
$email = $_POST['email'];   
$password = $_POST['password'];
$Room_No = $_POST['room_no'];
$Ext = $_POST['ext'];
//var_dump($_FILES);
$source = $_FILES['image']['tmp_name'];
$imageName = $_FILES['image']['name'];
move_uploaded_file($source , "../imgs/./$imageName");
//var_dump(move_uploaded_file($source , "../imgs/./$imageName"));
echo "</br>";

echo $name;
try {
    $stm = $connection->prepare("INSERT INTO user (name, email, password, room_no, ext, image) VALUES (?,?,?, ?, ?, ?)");
    $stm->execute(array($name, $email, $password, $Room_No, $Ext, $imageName));
    


}catch(Exception $e) {    
    echo $e->getMessage();

}





?>