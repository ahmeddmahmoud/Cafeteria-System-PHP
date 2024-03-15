<?php
// Include the DB class file
include_once '../db.php'; 
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $name = $_SESSION['name'];
    $user_id = $_SESSION['id'];
} else {
    // Redirect to login page if user is not logged in
    setcookie("msg", "You are not logged in, please login first");
    header("Location: ../login/login.php");
    exit(); // Stop further execution
}
// Create an instance of the DB class
$db = new DB(); 
// Query to fetch total number of records
$result = $db->getData("category");
?>

<!doctype html>
<html lang="en">
<head>
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/nav.css">
    <style>
        .category-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .category-item {
            position: relative;
            padding: 15px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
        }
        .delete-category {
            position: absolute;
            top: -5px;
            right: 2px;
            color: red;
            text-decoration: none;
            font-size: 20px;
        }

        .delete-category:hover {
            color: darkred; /* Change color on hover */
            text-decoration: none; /* Remove underline on hover */
        }
    </style>
</head>
<body>
    <?php
        include_once '../components/nav.php'; 
    ?>
    <!-- <nav class="navbar d-flex justify-content-between " style="background-color: #e3f2fd;">
        <div class="d-flex justify-align-content-between gap-3 px-5 ">
            <a href="#">Home</a>
            <a href="#">Products</a>
            <a href="#">Users</a>
            <a href="#">Manual Orders</a>
            <a href="#">Checks</a>
        </div>
        <div>
            <a href="#" class="px-5">Admin</a>
        </div>
    </nav> -->

    <section>
        <div class="text-center text-danger ">
            <h1>Available Categories</h1>
        </div>

        <div class="mx-5 my-2 text-center category-container">
            <?php 
            if ($result->num_rows === 0) {
                echo "<p>There are no categories available.</p>";
            } else {
                while ($row = $result->fetch_assoc()) {
                    // echo "<div class='category-item'>" . ucwords($row['name']) . "</div>";
                    echo "<div class='category-item'>" . ucwords($row['name']) . " <a href='deleteCategory.php?id={$row['id']}' class='delete-category'>&times;</a></div>";
                }
            }
            ?>
        </div>
    </section>

    <section>
        <div class="text-center my-5">
            <h1>Add New Category</h1>
        </div>
        <div>
            <form action="addCategory.php" method="post" class="needs-validation" novalidate>
                <div>
                    <label class="col-12 form-label text-center ">Category</label>
                    <input type="text" name="category" class="form-control" required >
                    <?php
                        if(isset($_COOKIE['errMsg'])){
                        $errorMessage = $_COOKIE['errMsg'];
                        echo "<p class='text-danger my-0 '>$errorMessage</p>";
                        setcookie('errMsg', '', time() -1);
                        }
                    ?>

                    <p class="invalid-feedback text-danger my-0 " style="font-size:16px">
                        Please add a category!
                    </p>
                </div>
                <div class="col-12 text-center">
                    <button class="btn btn-primary" type="submit">Add Category</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
        })()
    </script>
</body>
</html>

