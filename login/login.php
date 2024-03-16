<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- Font awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<?php
$errors = [];
// if (isset($_COOKIE['errMsg'])) {
//     var_dump($_COOKIE);
// }
if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
    // var_dump($errors);
}

?>
<style>
    body {
        background-color: #F4DFC8;

    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe login</title>
</head>

<body>
    <div class="card col-4 mx-auto my-5 text-center shadow">
        <div class="card-header">
            <h3>Log In</h3>
        </div>
        <form action="validation.php" method="POST" class="my-5 row g-3 needs-validation w-75 mx-auto">
            <div class="row">
                <div class="form-floating mb-2">
                    <?php if (!empty($errors)) : ?>
                        <div class="alert alert-danger">
                            <?php
                            // Check if $errors['connection'] is set and is an array
                            if (isset($errors['connection'])) {
                                echo "Please connect to another Database";
                            } else if (isset($errors['Connectio_Failed'])) {
                                echo "Connectio_Failed";
                            } else if (isset($errors['invalid'])) {
                                echo "Invalid email or password";
                            } else {
                                // Check for invalid email or password
                                echo "Invalid email or password";
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="input-group ">
                    <label for="email" class="input-group-text p-2"><i class="fa-solid fa-user"></i></label>
                    <input type="text" class="form-control rounded" id="email" placeholder="Email" name="email">
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                    <?php
                    if (isset($errors["email"])) {
                        echo "<span style='color:red'>" . $errors['email'] . "</span>";
                    }

                    ?>
                </div>
            </div>
            <div class="row">
                <div class="input-group my-3">
                    <label for="validationCustom02" class="input-group-text p-2"><i class="fa-solid fa-lock"></i></label>
                    <input type="password" class="form-control rounded" id="validationCustom02" placeholder="Password" name="password">
                    <div class="invalid-feedback">Please enter your password.</div>
                </div>
                <?php
                if (isset($errors['password'])) {
                    echo "<span style='color:red'>" . $errors["password"] . "</span>";
                }

                ?>
            </div>

            <div class="row-cols-2 justify-content-center text-center">
                <button class="btn btn-outline-secondary w-50 rounded-pill" name="login" type="submit">Log In</button>
            </div>

        </form>
        <div class="card-footer p-3"><a href="forgetpasswaord.php">Forget Your Password ?</a></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetching form elements
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('validationCustom02');

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

                // Password validation
                if (passwordInput.value === '') {
                    passwordInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    passwordInput.classList.remove('is-invalid');
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
</body>

</html>