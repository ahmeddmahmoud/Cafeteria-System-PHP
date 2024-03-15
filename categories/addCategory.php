<?php
// Include the DB class file
include_once '../db.php'; 

// Create an instance of the DB class
$db = new DB(); 

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST['category'])){
        $category=trim($_POST['category']);
        if(preg_match("/[^a-zA-Z0-9_\s-]/",$category)){
            setcookie("errMsg","Category cannot contain special characters!", time() + 1, "/");
            header('Location: categoryForm.php');
            exit();
        }
        if(strlen($category)<3){
            setcookie("errMsg","Category should be at least 3 characters!", time() + 1, "/");
            header('Location: categoryForm.php');
            exit();
        }
        $category=strtolower($category);
        $result = $db->getData("category", "name = '$category'");
        if ($result->num_rows > 0) {
            setcookie("errMsg","Category already exists!", time() + 1, "/");
            header('Location: categoryForm.php');
            exit();
        }
        $db->insert_data("category","name", "'$category'");
        //setcookie("successMsg","The Category Has been added successfully!");
        setcookie("successMsg", "The Category Has been added successfully!", time() + 1, "/");

        header('Location: ../products/productForm.php');
    }
}
?>