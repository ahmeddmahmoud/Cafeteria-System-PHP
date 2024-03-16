<?php
require_once '../db.php';
$errors = [];
try {
    $db = new DB();
} catch (Exception $e) {
    // Handle the database connection error gracefully by redirecting to the login page
    $errors["connection"] = 1;
    $errors = json_encode($errors);
    header("location:login.php?errors=" . $errors);
    exit();
}


$password = validate_data($_POST['new_password']); // Include password validation
$resetpassword=validate_data($_POST['reset_code']); 


    if (empty($password)) {
        $errors["password"] = "Please enter a password.";
    }
    if (empty($resetpassword)) {
        $errors["resetpassword"] = "Please enter a reset password.";
    }

    if (count($errors) > 0) {
        $errors = json_encode($errors);
        header("location:updatePassword.php?errors=" . $errors);
        exit(); // Stop further execution after redirection
    }else{
//******************************************************************************** */
session_start();

// if(isset($_GET['errors'])){
//     $errors = json_decode($_GET['errors'],true);
//     // var_dump($errors);
//  }
$errorMessage = ""; // Define errorMessage variable in the global scope

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_password"])) {
    $reset_code = $_POST["reset_code"];
    $new_password = $_POST["new_password"];
    $email =  $_SESSION['email'];

    if ($_SESSION['reset_code'] == $reset_code) {
        // Update the password in the database

        // $sql=$db->updateData("user", "password = ?"," email = ?");
        // $sql = $db->updateData("user", "password = '$new_password'", "email = '$email'");
        $sql = "UPDATE user SET password = ? WHERE email = ?";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $new_password, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // echo "Password updated successfully.";
            header("Location: login.php"); 
        } else {
            // $errorMessage = "Failed to update password.";
            $errorMessage = json_encode($errorMessage);
            header("location:updatePassword.php?errors=" . $errorMessage);
            // echo $email;
        }

        // Close connection
        $stmt->close();
        $db->getConnection()->close();
    } else {
        
        // $errorMessage = "Invalid reset code.";
        $errorMessage = json_encode($errorMessage);
        header("location:updatePassword.php?errors=" . $errorMessage);
    }
} else {
    // echo "Form submission error.";
}
        header("location:updatePassword.php");
    }


    
function validate_data($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

