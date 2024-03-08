<?php
require "../db.php";
function validateData($data) {
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$db  = new Db();
$db->__construct();
//$connection = $db->get_connection();


// if ($connection->connect_error) {
//     die("connection failed");

// }
//$id = $_POST['id'];
$name = validateData($_POST['name']); 
$email =validateData($_POST['email']);   

$password = validateData($_POST['password']);
echo $_POST['password'];
$Room_No = validateData($_POST['room_no']);
$Ext = validateData($_POST['ext']);
//var_dump($_FILES);
$source = $_FILES['image']['tmp_name'];
$imageName = $_FILES['image']['name'];
move_uploaded_file($source , "../imgs/./$imageName");
//var_dump(move_uploaded_file($source , "../imgs/./$imageName"));
echo "</br>";

//echo $name;
try {
    if (isset($_POST['add'])) {
        $db->insert_data("rooms" , "room_no , ext" , "'$Room_No' , '$Ext'");
        $db->insert_data("user" , "name , email , password , room_no, image , role" , "'$name' , '$email' , '$password'  , '$Room_No', '$imageName' , 'user'");  
    }
    elseif(isset($_POST['update'])){
        $db->update_data("rooms" , "room_no = '$Room_No' , ext = '$Ext'" , "room_no = '$Room_No'");
        $db->update_data("user" , "name = '$name' , email = '$email' , password = '$password' , room_no = '$Room_No' , image = '$imageName'" , "id = '$id'");
    }
    
    
    
    


}catch(Exception $e) {    
    echo $e->getMessage();

}





?>