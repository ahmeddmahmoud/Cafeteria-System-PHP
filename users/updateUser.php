<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<?php

$id  = $_GET['id'];
require "../db.php";
$db = new DB();
$db->__construct();
$data = $db->getData("users" , "id = '$id'");
$result = $data -> fetch_array(MYSQLI_ASSOC);

?>


<div class="container">
    <form action="" method="post">
        <div class="mb-3">
            <label for="">ID</label>
            <input type="text" name="id" class="form-control" value="<? $result['id'] ?>" readonly>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<? $result['name'] ?>" >
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<? $result['email'] ?>">
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" value="<? $result['password'] ?>">
        </div>
        <div class="mb-3">
            <label for="password">Confirm Password</label>
            <input type="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="Room No">Room No.</label>
            <input type="text" name="room_no" class="form-control" value="<? $result['room_no'] ?>">
        </div>
        <div class="mb-3">
            <label for="Ext">Ext.</label>
            <input type="text" name="ext" class="form-control" value="<? $result['ext'] ?>">
        </div>
        <div class="mb-3">
            <label for="image">Profile Picture</label value="<? $result['image'] ?>">
            <input type="file" class="form-control" name="image">
        </div>
            <button type="submit" value="save" name="save" class="btn btn-primary my-3">Save</button>
            <button type="button" class="btn btn-danger ms-5" name="update" value="update">Cancel</button>
    </form>

</div>