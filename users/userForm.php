<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<div class="container">
    <form action="addUser.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password">Confirm Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="Room No">Room No.</label>
            <input type="text" name="room_no" class="form-control">
        </div>
        <div class="mb-3">
            <label for="Ext">Ext.</label>
            <input type="text" name="ext" class="form-control">
        </div>
        <div class="mb-3">
            <label for="image">Profile Picture</label>
            <input type="file" class="form-control" name="image">
        </div>
            <button type="submit" value="save" name="save" class="btn btn-primary my-3">Save</button>
            <button type="button" class="btn btn-danger ms-5">Cancel</button>
    </form>

</div>
