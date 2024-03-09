<?php
require "../db.php";
function validateData($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$db  = new Db();
$db->__construct();
$errors = [];

// $id = $_POST['id'];

$name = validateData($_POST['name']);
echo $name;
echo "</br>";
$email = validateData($_POST['email']);

$password = validateData($_POST['password']);
$confirmPassword = validateData($_POST['confirm_password']);

$Room_No = validateData($_POST['room_no']);
echo $Room_No;
echo "</br>";

$Ext = validateData($_POST['ext']);
echo $Ext;
echo "</br>";

//var_dump($_FILES);/
$source = $_FILES['image']['tmp_name'];
$imageName = $_FILES['image']['name'];
move_uploaded_file($source, "../imgs/users/" . $imageName);

echo "</br>";

//echo $name;
try {
    if (strlen($name) < 3 || !preg_match("/^[a-zA-Z]+$/", $name)) {
        $errors['name'] = "Name must be at least 3 characters and characters only";
    }
    // check email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email";
    }
    //check password length
    if (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    }
    // if ($password !== $confirmPassword){
    //     $errors['confirm_password'] = "Password does not match";
    // }
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



    if (count($errors) > 0) {
        $errors = json_encode($errors);
        if (isset($_POST['add'])) {
            header("location: userForm.php?errors=" . $errors);
        } else {
            header("location: updateUser.php?errors=" . $errors);
        }
    } else {

        if (isset($_POST['add'])) {
            // $db->insert_data("rooms" , "room_no , ext" , "'$Room_No' , '$Ext'");
            $db->insert_data("user", "name , email , password , room_no, image , role", "'$name' , '$email' , '$password'  , '$Room_No', '$imageName' , 'user'");
        } elseif (isset($_POST['update'])) {

            $db->update_data("rooms", "room_no = '$Room_No' , ext = '$Ext'", "room_no = '$Room_No'");
            $db->update_data("user", "name = '$name' , email = '$email' , password = '$password' , room_no = '$Room_No'", "id = '$id'");
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
