<?php
require_once '../db.php';

try {
    $db = new DB();
} catch (Exception $e) {
    // Handle the database connection error gracefully by redirecting to the login page
    header("Location: login.php?error=Invalid_dbConnection");
    exit();
}


$errors = [];
// var_dump($_POST);
$email = validate_data($_POST['email']);
$password = validate_data($_POST['password']); // Include password validation

try {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Please provide a valid email address";
    }

    if (empty($password)) {
        $errors["password"] = "Please enter a password.";
    }

    if (count($errors) > 0) {
        $errors = json_encode($errors);
        header("location:login.php?errors=" . $errors);
        exit(); // Stop further execution after redirection
    } else {

        $result = $db->getData("user", "email='$email' AND password='$password'");

        // $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        // $result = $connection->query($query);

        if (!$result) {
            header("Location: login.php?errors=db_error");
        }

        $data = $result->fetch_array(MYSQLI_ASSOC);
        if ($data != NULL) {
            // Start the session if it's not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // Store user data in session
            $_SESSION['email'] = $data['email'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['id'] = $data['id'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['image'] = $data['image'];
            $_SESSION['room_no'] = $data['room_no'];


            if ($_SESSION['role'] == 'admin') {
                header("Location: ../orders/makeOrderAdmin.php");
            } else {
                header("Location: ../orders/makeOrderUser.php");
            }
            exit();
        } else {

            header("Location: login.php?errors=1");
            exit();
        }

        $connection->close();
    }
} catch (Exception $e) {
    header("Location: login.php?errors=db_error");
}

function validate_data($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
