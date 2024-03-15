<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<?php

//include "../components/nav.php";

if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true); // Decode the JSON string into an associative array
}


?>

<div class="container">
    <form action="addUser.php" method="post" enctype="multipart/form-data" id="registrationForm">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
            <p class="text-danger" id="nameError"><?php if (isset($errors['name'])) echo $errors['name']; ?></p>
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
            <p class="text-danger"><?php if (isset($errors['email'])) echo $errors['email']; ?></p>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <p class="text-danger"><?php if (isset($errors['password'])) echo $errors['password']; ?></p>
        </div>
        <div class="mb-3">
            <label for="password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            
            <p class="text-danger" id="passwordError"><?php if (isset($errors['confirm_password'])) echo $errors['confirm_password']; ?></p>
        </div>
        <div class="mb-3">
            <label for="Room No">Room No.</label>
            <input type="text" name="room_no" class="form-control" required>
            <p class="text-danger"><?php if (isset($errors['room_no'])) echo $errors['room_no']; ?></p>
        </div>
        <div class="mb-3">
            <label for="Ext">Ext.</label>
            <input type="text" name="ext" class="form-control" required>
            <p class="text-danger"><?php if (isset($errors['ext'])) echo $errors['ext']; ?></p>
        </div>
        <div class="mb-3">
            <label for="image">Profile Picture</label>
            <input type="file" class="form-control" name="image" required>
            <p class="text-danger"><?php if (isset($errors['image'])) echo $errors['image']; ?></p>
        </div>
            <button type="submit" value="add" name="add" class="btn btn-primary my-3">Save</button>
            <button type="button" class="btn btn-danger ms-5">Cancel</button>
    </form>

</div>

<script>
document.getElementById('registrationForm').addEventListener('submit', function(event) {
     var name = document.getElementById('name').value;
    var nameError = document.getElementById('nameError');
    var lettersRegex = /^[A-Za-z]+$/;

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
