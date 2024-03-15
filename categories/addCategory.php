<?php
// Include the DB class file
include_once '../db.php'; 

// Create an instance of the DB class
$db = new DB(); 

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST['category'])){
        $category=trim($_POST['category']);
        if(preg_match("/[^a-zA-Z0-9_\s-]/",$category)){
            setcookie("errMsg","Category cannot contain special characters!");
            header('Location: categoryForm.php');
            exit();
        }
        if(strlen($category)<3){
            setcookie("errMsg","Category should be at least 3 characters!");
            header('Location: categoryForm.php');
            exit();
        }
        $category=strtolower($category);
        $result = $db->getData("category", "name = '$category'");
        if ($result->num_rows > 0) {
            setcookie("errMsg","Category already exists!");
            header('Location: categoryForm.php');
            exit();
        }
        $db->insert_data("category","name", "'$category'");
        header('Location: categoryForm.php');
    }
}
?>