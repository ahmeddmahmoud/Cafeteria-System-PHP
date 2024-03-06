<?php
$errors = [];
var_dump($_POST);
$email = validate_data($_POST['email']);
$password = validate_data($_POST['password']); // Include password validation

try {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Please provide a valid email address";
    }

    if (empty($password)) {
        $errors["password"] = "Please enter a password.";
    }

    // You can add more validations as needed for other fields

    if (count($errors) > 0) {
        $errors = json_encode($errors);
        header("location:login.php?error=" . $errors);
        exit(); // Stop further execution after redirection
    } else {

        // Perform database operations securely (consider using prepared statements)
        $connection = new mysqli("localhost", "root", "gg4019268", "cafeteria_DB");
        if ($connection->connect_errno) {
            throw new Exception("Failed to connect to MySQL: " . $connection->connect_error);
        }

        $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        $result = $connection->query($query);

        if (!$result) {
            throw new Exception("Query failed: " . $connection->error);
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

            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_dashboard.php"); 
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            header("Location: login.php?error=1");
            exit();
        }

        $connection->close();
    }
} catch (Exception $e) {
    var_dump($e->getMessage());
}

function validate_data($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
