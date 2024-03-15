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
session_start();

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
            $errorMessage = "Failed to update password.";
            // echo $email;
        }

        // Close connection
        $stmt->close();
        $db->getConnection()->close();
    } else {
        
        $errorMessage = "Invalid reset code.";
    }
} else {
    // echo "Form submission error.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
<div class="card col-4 mx-auto my-5 text-center shadow">
    <div class="card-header"><h3>Reset Password</h3></div>
    <div class="container">
                <form action="" method="POST" autocomplete="off">
                    <p class="text-center">Enter the reset code and your new password</p>
                         <!-- Error message display area -->
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                    <div class="form-group">
                        <input class="form-control" type="text" name="reset_code" placeholder="Enter reset code" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="new_password" placeholder="Enter new password" required>
                    </div>
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="update_password" value="Update Password">
                    </div>
                </form>
               
            </div>
        </div>
    </div>
    </div>
    </div>

</body>
</html>
<style>
    body{
        background-color: #F4DFC8;
        
    }
</style>