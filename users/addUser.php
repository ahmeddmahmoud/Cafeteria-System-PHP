<?php

function validateData($data) {
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
require "../db.php";
$db  = new Db();
$db->__construct();
$connection = $db->get_connection();



// if ($connection->connect_error) {
//     die("connection failed");

// }
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
    $stm = $connection->prepare("INSERT INTO user (name, email, password,  image) VALUES (?,?,?, ?)");
    $stm->execute(array($name, $email, $password, $imageName));
    


}catch(Exception $e) {    
    echo $e->getMessage();

}





?>