<?php 
    //Checking 
    require_once '../functions/validateSourcePage.php';
    if (!isset($_GET['id'])){
        validateSourcePage('categoryForm.php', '../errors/err.php', 403);
    }

    //Getting Variables
    $id=$_GET["id"];

    include_once '../db.php'; 
    $db = new DB();
    $db->delete("category", "id=$id");

    //Return to Category Page
    header("Location: categoryForm.php?");
?>