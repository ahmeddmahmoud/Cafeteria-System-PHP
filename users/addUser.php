<?php



require "../db.php";
function validateData($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Check if the referer is not set or if it doesn't contain 'userForm.php'
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'userForm.php') === false) {
    // Redirect to userForm.php
    header('Location: ./userForm.php');
    exit; // It's a good practice to exit after sending a Location header
}


$db  = new DB();
$db->__construct();
$errors = [];



$name = validateData($_POST['name']);
//echo $name;
echo "</br>";
$email = validateData($_POST['email']);

$password = validateData($_POST['password']);
$confirmPassword = validateData($_POST['confirm_password']);

$Room_No = validateData($_POST['room_no']);

echo "</br>";

$Ext = validateData($_POST['ext']);

echo "</br>";

//var_dump($_FILES);/
$source = $_FILES['image']['tmp_name'];
$imageName = $_FILES['image']['name'];
move_uploaded_file($source, "../imgs/users/" . $imageName);

echo "</br>";
// for update
if (isset($_POST['update'])){
    $id = $_POST['id'];
session_start();
$oldRoom = $_SESSION['roomNo'];
}


//echo $name;
try {
    if (strlen($name) < 3 || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
    $errors['name'] = "Name must be at least 3 characters and contain only alphabetic characters";
}

    // check email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email";
    }
    //check password length
    if (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    }
    if ($password !== $confirmPassword){
        $errors['confirm_password'] = "Password does not match";
    }
    if (!is_numeric($Room_No)) {
        $errors['room_no'] = "Room No. must be numeric";
    }
    if (!is_numeric($Ext)) {
        $errors['ext'] = "Ext. must be numeric";
    }
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        // There was an error uploading the file
        $errors['image'] = "Image upload failed. ";
    }
    $checkEmail = $db->getData("user" , "email = '$email'");
    if ($checkEmail !== null){
        $errors['email'] = "This User already exists";
        header("location: userForm.php?errors=" . $errors);
        exit;
    }



    if (count($errors) > 0) {
        $errors = json_encode($errors);
        if (isset($_POST['add'])) {
            header("location: userForm.php?errors=" . $errors);
            exit;
        } else if(isset($_POST['update'])) {
            header("location: updateUser.php?errors=" . $errors."&id=" . $id);
            exit;
        }
    } else {

        if (isset($_POST['add'])) {
            $db->insert_data("rooms" , "room_no , ext" , "'$Room_No' , '$Ext'");
            $db->insert_data("user", "name , email , password , room_no, image , role", "'$name' , '$email' , '$password'  , '$Room_No', '$imageName' , 'user'");
        } elseif (isset($_POST['update'])) {
            $db->update_data("rooms" , "room_no = '$Room_No' , ext = '$Ext'" , "room_no = '$oldRoom'");
            $db->update_data("user" , "name = '$name' , email = '$email' , password = '$password' , room_no = '$Room_No' , image = '$imageName'", "id = '$id'");
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
