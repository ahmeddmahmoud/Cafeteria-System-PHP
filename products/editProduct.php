 <?php
session_start();
// Check if admin is logged in
    if ($_SESSION['role'] == 'admin') {
    $name = $_SESSION['name'];
    $user_id = $_SESSION['id'];
} else {
    // Redirect to login page if user is not logged in
    setcookie("msg", "You are not logged in, please login first");
    header("Location: ../login/login.php");
    exit(); // Stop further execution
}
$errors=[];
if(isset($_GET['errors'])){
    $errors=json_decode($_GET['errors'],true);
}


//open connection
$connection = new mysqli("localhost", "php", "1234", "cafe");

if ($connection->connect_errno) {
    die("Connection failed...");
    
}
$query = "SELECT * FROM category";
$result = $connection->query($query);

$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>


 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" /> -->
    <div class="container">
    <form action="addproduct.php" method="post" class="my-2 row g-3 needs-validation" novalidate enctype="multipart/form-data">
            <div class="row my-4">
                <label for="validationCustom01" class="form-label">Product</label>
                <input type="text" class="form-control" id="validationCustom01" placeholder="Product" required name="productname">
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter a product name.</div>
            </div>
            <div class="row">
                <label for="priceinput" class="form-label">Price</label>
                <input type="Number" name="price" class="form-control" id="priceinput" min="1" step="any" placeholder="Price" required>
                <div class="invalid-feedback">Please enter a valid price.</div>
            </div>
            <div class="row my-4">
                <label for="validationCustom04" class="form-label">Category</label>
                <select name="category" class="form-select form-control p-3" id="validationCustom04" required>
                    <option selected value="">Select Category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select a category.</div>
            </div>
            <div class="row justify-content-center">
                <button type="button" class="btn btn-primary my-2 w-25">Add Category</button>
            </div>
            <div class="row">
                <label class="form-label">Image</label>
                <input type="file" name="img" class="form-control" accept="image/*">
            </div>
        <div class="row justify-content-center my-5">
            <button class="btn btn-primary w-auto" type="submit">Submit</button>
            <button type="reset" class="btn btn-primary mx-3 w-auto">Reset</button>
        </div>
    </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
        <script>
    // JavaScript to enable Bootstrap's client-side form validation
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
</script>

