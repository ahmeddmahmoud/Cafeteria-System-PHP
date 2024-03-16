<?php



require "../db.php";
function validateData($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$errors = [];



// require_once '../functions/validateSourcePage.php';
// if (!isset($_POST['add']) || !isset($_POST['update'])) {
//     // validateSourcePage('userForm.php', '../errors/err.php', 403);
//     header('Location: ../errors/err.php?err=403');
// }

$db  = new DB();
$db->__construct();
$name = validateData($_POST['name']);

$email = validateData($_POST['email']);

$password = validateData($_POST['password']);
$confirmPassword = validateData($_POST['confirm_password']);

$Room_No = validateData($_POST['room_no']);

$Ext = validateData($_POST['ext']);


$source = $_FILES['image']['tmp_name'];
$imageName = $_FILES['image']['name'];
move_uploaded_file($source, "../imgs/users/" . $imageName);

echo "</br>";
// for update
if (isset($_POST['update'])) {
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
    if ($password !== $confirmPassword) {
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


    if (count($errors) > 0) {
        $errors = json_encode($errors);
        if (isset($_POST['add'])) {
            header("location: userForm.php?errors=" . $errors);
        } else if (isset($_POST['update'])) {
            header("location: updateUser.php?errors=" . $errors . "&id=" . $id);
        }
    } else {
        // check room number exist or not get_Data
        // if exist roomno ----> table users
        // update roomno check  exist or not 
        $checkExistRoom = $db->getData("rooms", "room_no = '$Room_No'" , "room_no");
        
        // var_dump($checkExistRoom->fetch_all());
        // die();
        
        if (isset($_POST['add'])) {
            if ($checkExistRoom == null){
                
                $db->insert_data("rooms", "room_no , ext", "'$Room_No' , '$Ext'");
            }
            
            $db->insert_data("user", "name , email , password , room_no, image , role", "'$name' , '$email' , '$password'  , '$Room_No', '$imageName' , 'user'");
        } elseif (isset($_POST['update'])) {
            // $db->update_data("rooms", "room_no = '$Room_No' , ext = '$Ext'", "room_no = '$oldRoom'");
            if ($checkExistRoom == null){
                
                $db->insert_data("rooms", "room_no , ext", "'$Room_No' , '$Ext'");
            }
            $db->update_data("user", "name = '$name' , email = '$email' , password = '$password' , room_no = '$Room_No' , image = '$imageName'", "id = '$id'");
        }
        header("location: usersTable.php");
    }
} catch (Exception $e) {
    if ($e->getCode() === 1062) { // MySQL error code for duplicate entry
        
        if (strpos($e->getMessage(), 'email') !== false) { // Check if the error message contains 'email'
            $errors['email'] = "Email already exists";
        } else {
            // For other duplicate entry errors or unknown errors, you can display a generic error message
            echo "An error occurred: " . $e->getMessage();
            exit; // Exit the script to prevent further execution
        }

        $errors = json_encode($errors);
        if (isset($_POST['add'])) {
            header("location: userForm.php?errors=" . $errors);
        } elseif (isset($_POST['update'])) {
            header("location: updateUser.php?errors=" . $errors . "&id=" . $id);
        }
    } else {
        // For other exceptions, you can display a generic error message
        echo "An error occurred: " . $e->getMessage();
    }
}


