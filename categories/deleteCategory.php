<?php 
    //Getting Variables
    $id=$_GET["id"];

    include_once '../db.php'; 
    $db = new DB();
    $db->delete("category", "id=$id");

    //Return to Category Page
    header("Location: categoryForm.php?");
?>