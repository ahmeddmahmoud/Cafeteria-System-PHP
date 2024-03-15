<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<?php

require_once '../functions/validateSourcePage.php';
//validateSourcePage('usersTable.php', '../errors/err.php', 403);


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
<style>
body{
    background-color: #F4EAE0 !important;
}
</style>


<div class="card w-50 my-2 mx-auto">
    <div class="card-header text-center"><h3>Add New User</h3></div>
    <form action="addUser.php" method="post" enctype="multipart/form-data">
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="id" placeholder="ID" class="form-control" value="<?= $result['id'] ?>" readonly>
            <label for="id">ID</label>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="name" class="form-control" value="<?= $result['name'] ?>" placeholder="name">
            <label for="name">Name</label>
            <p class="text-danger"><?php if (isset($errors['name'])) echo $errors['name']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="email" name="email" class="form-control" value="<?= $result['email'] ?>" placeholder="email">
            <label for="email">Email</label>
            <p class="text-danger"><?php if (isset($errors['email'])) echo $errors['email']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="password" name="password" class="form-control" value="<?= $result['password'] ?>" placeholder="password">
            <label for="password">Password</label>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="password" name="confirm_password" class="form-control" placeholder="confirm password">
            <label for="password">Confirm Password</label>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="room_no" class="form-control" value="<?= $result['room_no'] ?>" placeholder="roomNo">
            <label for="Room No">Room No.</label>
            <p class="text-danger"><?php if (isset($errors['room_no'])) echo $errors['room_no']; ?></p>
        </div>
        <div class="my-2 form-floating w-75 mx-auto">
            <input type="text" name="ext" class="form-control" placeholder="ext" value="<?= $roomResult['ext'] ?>">
            <label for="Ext">Ext.</label>
            <p class="text-danger"><?php if (isset($errors['ext'])) echo $errors['ext']; ?></p>
        </div>
        <div class="my-2  w-75 mx-auto">
            <label for="image">Profile Picture</label >
            <input type="file" class="form-control" name="image" required value="<?= $result['image'] ?>">
            <p class="text-danger"><?php if (isset($errors['image'])) echo $errors['image']; ?></p>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary" name="update" value="update">Save</button>
            <a href="./usersTable.php" class="btn btn-danger">Cancel</a>
        </div>
    </form>

</div>