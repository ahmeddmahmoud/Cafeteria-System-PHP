<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<?php
$errors=[];
if(isset($_REQEST['error'])){
   $errors=json_decode($_GET['error'],true);

}

?>
<form action="validation.php" method="POST" class="my-5 row g-3 needs-validation w-50 mx-auto" >
    <div class="row">
        <div class="form-floating mb-2">
            <input type="email" class="form-control" id="email" placeholder="Email" required name="email">
            <label for="email" class="px-4">Email</label>
            <div class="invalid-feedback">Please enter a valid email address.</div>
            
        </div>
    </div>
    <div class="row">
        <div class="form-floating mb-2">
            <input type="password" class="form-control" id="validationCustom02" placeholder="Password" required name="password">
            <label for="validationCustom02" class="px-4">Password</label>
            <div class="invalid-feedback">Please enter your password.</div>
        </div>
    </div>
    
    <div class="row-cols-2 justify-content-center text-center">
        <button class="btn btn-primary w-auto" name="login" type="submit">Login</button>
    </div>
    <a href="forgetpasswaord.php">Forget Your Password?</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


