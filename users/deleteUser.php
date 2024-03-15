<?php 
    //Checking
    require_once '../functions/validateSourcePage.php';
    if (!isset($_GET['id'])){
        validateSourcePage('userForm.php', '../errors/err.php', 403);
    }

    //Getting Variables
    $id=$_GET["id"];
    $page=$_GET["page"];

    include_once '../db.php'; 
    $db = new DB();
    $db->delete("user", "id=$id");

    //Return to Users Page
    header("Location: usersTable.php?page=$page");
?>