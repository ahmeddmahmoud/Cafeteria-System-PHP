<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- Font awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />


<?php
$errors = [];
if(isset($_GET['errors'])){
    $errors = json_decode($_GET['errors'],true);
 }

?>

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
        <?php
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
            ?>
            <div class="card col-12 mx-auto  my-5 text-center shadow">
            <div class="card-header"><h3>Forget Your Password</h3></div>
                <form action="validation_forgetpass.php" method="POST" autocomplete="off">
                <div class="row">
                    <!-- <h2 class="text-center">Forgot Password</h2> -->
                    <p class="text-center">Enter your email address</p>
                    
                    <div class="form-group">
                        <input class="form-control" type="text" name="email" id="email" placeholder="Enter email address"  value="<?php echo isset($email) ? $email : '' ?>">
                        <?php
                        if (isset($errors["email"])) {
                            echo "<span style='color:red'>" . $errors['email'] . "</span>";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Continue">
                    </div>
                    <div >
                </form>
                </div>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
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
?>
<style>
    body{
        background-color: #F4DFC8;
        
    }
</style>
<!-- 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fetching form elements
        const form = document.querySelector('form');
        const emailInput = document.getElementById('email');


        // Adding event listener to form submission
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Email validation
            if (!validateEmail(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
            }

            // Preventing form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });

        // Email validation function
        function validateEmail(email) {
            const pass = /\S+@\S+\.\S+/;
            return pass.test(email);
        }
    });
</script>



 -->
