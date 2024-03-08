<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<?php
$errors = [];
if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
    //    var_dump($errors);
}
?>



<form action="validation.php" method="POST" class="my-5 row g-3 needs-validation w-50 mx-auto">
    <div class="row">
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="email" placeholder="Email" name="email">
            <label for="email" class="px-4">Email</label>
            <div class="invalid-feedback">Please enter a valid email address.</div>
            <?php
            if (isset($errors["email"])) {
                echo "<span style='color:red'>" . $errors['email'] . "</span>";
            }

            ?>
        </div>
    </div>
    <div class="row">
        <div class="form-floating mb-2">
            <input type="password" class="form-control" id="validationCustom02" placeholder="Password" name="password">
            <label for="validationCustom02" class="px-4">Password</label>
            <div class="invalid-feedback">Please enter your password.</div>
        </div>
        <?php
        if (isset($errors['password'])) {
            echo "<span style='color:red'>" . $errors["password"] . "</span>";
        }

        ?>
    </div>

    <div class="row-cols-2 justify-content-center text-center">
        <button class="btn btn-primary w-auto" name="login" type="submit">Login</button>
    </div>
    <a href="forgetpasswaord.php">Forget Your Password?</a>
</form>

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