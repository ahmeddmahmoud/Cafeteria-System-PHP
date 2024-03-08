<?php 
    //Getting Variables
    $id=$_GET["id"];
    $page=$_GET["page"];

    include_once '../db.php'; 
    $db = new DB();
    $db->delete("user", "id=$id");

    //Return to Users Page
    header("Location: usersTable.php?page=$page");
?>