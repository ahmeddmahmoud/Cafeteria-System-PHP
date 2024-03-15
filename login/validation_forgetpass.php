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

session_start(); // Start the session
// Check if the session variable is set
if (isset($_SESSION['wrong_email']) && $_SESSION['wrong_email']) {
    // Display the error message
    echo '<div class="form-floating mb-2">';
    echo '<div class="alert alert-danger">';
    echo "This email doesn’t found in Database";
    echo '</div>';
    echo '</div>';
    
    // Unset the session variable to avoid showing the message again on subsequent page loads
    unset($_SESSION['wrong_email']);

}else if (isset($_SESSION['failed_sent']) && $_SESSION['failed_sent']){
        // Display the error message
        echo '<div class="form-floating mb-2">';
        echo '<div class="alert alert-danger">';
        echo "Failed to send the code. Please try again later.";
        echo '</div>';
        echo '</div>';
        
        // Unset the session variable to avoid showing the message again on subsequent page loads
        unset($_SESSION['failed_sent']);
    }




// var_dump($_POST);
$email = validate_data($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Please provide a valid email address";
    }
    if (empty($email)) {
        $errors["email"] = "Email Address Can't Be Empty.";
    }

    if (count($errors) > 0) {
        $errors = json_encode($errors);
        header("location:forgetpasswaord.php?errors=" . $errors);
        exit(); // Stop further execution after redirection
    }else{
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

<?php
require_once '../db.php';
try {
    $db = new DB();
    $errors = [];
} catch (Exception $e) {
    // Handle the database connection error gracefully by redirecting to the login page
    $errors["connection"] = 1;
    $errors = json_encode($errors);
    header("location:login.php?errors=" . $errors);
    exit();
}

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
            $_SESSION['failed_sent'] = true;
            header("Location: forgetpasswaord.php");
            // echo "Failed to send the code. Please try again later.";
            
        }
    } else {
        $_SESSION['wrong_email'] = true;
        header("Location: forgetpasswaord.php");
        // echo "Email does not exist in the database. Please enter a valid email.";
    }
    $stmt->close();

}
?>

