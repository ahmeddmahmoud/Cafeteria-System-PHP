<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<?php

$id  = $_GET['id'];

require "../db.php";
$db = new DB();
$db->__construct();
// $data = $db->select_data("user", "id = '$id'");
$data = $db->getData("user" , "id = '$id' ");
$result = $data->fetch_array(MYSQLI_ASSOC);

// die();
$roomNo = $result['room_no'];
// $roomData = $db->select_data("rooms", "room_no = '$roomNo' ");
$roomData = $db->getData("rooms",  "room_no = '$roomNo' ");
$roomResult = $roomData->fetch_array(MYSQLI_ASSOC);
echo "</br>";

session_start();
$_SESSION['roomNo'] = $roomNo;

if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true); // Decode the JSON string into an associative array
    
}


?>


<div class="container">
    <form action="addUser.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="">ID</label>
            <input type="text" name="id" class="form-control" value="<?= $result['id'] ?>" readonly>

        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= $result['name'] ?>" required>
            <p class="text-danger"><?php if (isset($errors['name'])) echo $errors['name']; ?></p>
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= $result['email'] ?>">
            <p class="text-danger"><?php if (isset($errors['email'])) echo $errors['email']; ?></p>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required value="<?= $result['password'] ?>">
            <p><p class="text-danger"><?php if (isset($errors['password'])) echo $errors['password']; ?></p>
        </div>
        <div class="mb-3">
            <label for="password">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control">
            <p class="text-danger"><?php if (isset($errors['confirm_password'])) echo $errors['confirm_password']; ?></p>
        </div>
        <div class="mb-3">
            <label for="Room No">Room No.</label>
            <input type="text" name="room_no" class="form-control" required value="<?= $result['room_no'] ?>">
            <p class="text-danger"><?php if (isset($errors['room_no'])) echo $errors['room_no']; ?></p>
        </div>
        <div class="mb-3">
            <label for="Ext">Ext.</label>
            <input type="text" name="ext" class="form-control" required value="<?= $roomResult['ext'] ?>">
            <p class="text-danger"><?php if (isset($errors['ext'])) echo $errors['ext']; ?></p>
        </div>
        <div class="mb-3">
            <label for="image">Profile Picture</label >
            <input type="file" class="form-control" name="image" required value="<?= $result['image'] ?>">
            <p class="text-danger"><?php if (isset($errors['image'])) echo $errors['image']; ?></p>
        </div>
        <button type="submit" class="btn btn-primary my-3" name="update" value="update">Save</button>
        <button type="button" class="btn btn-danger ms-5">Cancel</button>
    </form>

</div>