<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="" method="POST" autocomplete="off">
                    <h2 class="text-center">Forgot Password</h2>
                    <p class="text-center">Enter your email address</p>
                    
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Enter email address" required value="<?php echo isset($email) ? $email : '' ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// echo $_post['email'];
?>

<?php
require_once '../db.php';
try {
    $db = new DB();
} catch (Exception $e) {
    // Handle the database connection error gracefully by redirecting to the login page
    header("Location: login.php?error=Invalid_dbConnection");
    exit();
}
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["check-email"])) {
    // Retrieve the email entered by the user
    $email = $_POST["email"];

    // Prepare and execute the query to check if the email exists in the database
    
    // $result=$db->getData("user","email ={$_POST['email']}}");
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $db->getConnection()->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row with the given email exists
    if ($result->num_rows > 0) {
     
        $code = rand(100000, 999999);

        // Save the code in a session variable
        $_SESSION['reset_code'] = $code;
        $_SESSION['email'] = $email;


        // Send the code to the user's email
        $subject = "Password Reset Code";
        $message = "Your password reset code is $code";
        $sender = "From: mohamed.awad.elgammal@gmail.com";
        if (mail($email, $subject, $message, $sender)) {
            echo "We've sent a password reset code to your email - $email";
            header("Location: updatePassword.php?email=" . urlencode($email));
        } else {
            echo "Failed to send the code. Please try again later.";
        }
    } else {
        echo "Email does not exist in the database. Please enter a valid email.";
    }

    // Close connection
    $stmt->close();
    // $connection->close();
}
?>
