<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<?php




require_once '../functions/validateSourcePage.php';

//validateSourcePage('usersTable.php', '../errors/err.php', 403);


if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true); // Decode the JSON string into an associative array
}


?>
<style>
    body {
        background-color: #F4EAE0 !important;
    }
</style>

<div class="card w-50 my-2 mx-auto">
    <div class="card-header text-center">
        <h3>Add New User</h3>
    </div>
    <form action="addUser.php" method="post" enctype="multipart/form-data" id="registrationForm">
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="name" id="name" class="form-control" placeholder="name" required>
            <label for="name">Name</label>
            <p class="text-danger" id="nameError"><?php if (isset($errors['name'])) echo $errors['name']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="email" name="email" class="form-control" placeholder="email" required>
            <label for="email">Email</label>
            <p class="text-danger"><?php if (isset($errors['email'])) echo $errors['email']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="password" name="password" id="password" class="form-control" placeholder="password" required>
            <label for="password">Password</label>
            <p class="text-danger"><?php if (isset($errors['password'])) echo $errors['password']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
            <label for="password">Confirm Password</label>

            <p class="text-danger" id="passwordError"><?php if (isset($errors['confirm_password'])) echo $errors['confirm_password']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="room_no" class="form-control" placeholder="roomNo" required>
            <label for="Room No">Room No.</label>
            <p class="text-danger"><?php if (isset($errors['room_no'])) echo $errors['room_no']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="ext" class="form-control" placeholder="ext" required>
            <label for="Ext">Ext.</label>
            <p class="text-danger"><?php if (isset($errors['ext'])) echo $errors['ext']; ?></p>
        </div>
        <div class="my-2  w-75 mx-auto">
            <label for="image">Profile Picture</label>
            <input type="file" class="form-control" name="image" required>
            <p class="text-danger"><?php if (isset($errors['image'])) echo $errors['image']; ?></p>
        </div>
        <div class="card-footer text-center">
            <button type="submit" value="add" name="add" class="btn btn-primary ">Save</button>
            <a href="./usersTable.php" class="btn btn-danger">Cancel</a>
        </div>
    </form>

</div>

<script>
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        var name = document.getElementById('name').value;
        var nameError = document.getElementById('nameError');
        var lettersRegex = /^[A-Za-z\s]+$/;

        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;
        var passwordError = document.getElementById('passwordError');

        if (password !== confirmPassword) {
            passwordError.textContent = "Passwords do not match";
            event.preventDefault(); // Prevent form submission
        } else {
            passwordError.textContent = ""; // Clear any previous error message
        }
        if (!name.match(lettersRegex)) {
            nameError.textContent = "Name must contain only letters";
            event.preventDefault(); // Prevent form submission
        } else {
            nameError.textContent = ""; // Clear any previous error message
        }
    });
</script>