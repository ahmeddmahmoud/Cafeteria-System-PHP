<?php
include_once '../db.php'; // Include the DB class file
//open connection
// $connection = new mysqli("localhost", "php", "1234", "cafe");
$db=new DB();
$db->__construct();
// if ($connection->connect_errno) {
//     die("Connection failed...");
    
// }
//query
require_once '../functions/validateSourcePage.php';
if (!isset($_POST['productname'])) {
    validateSourcePage('productForm.php', '../errors/err.php',403);
}
try {
    $nameInput = validate_data($_POST['productname']);
    $name=ucwords($nameInput);
    $price = validate_data($_POST['price']);
    $category =validate_data($_POST['category']);
    $errors=[];
    $img=$_FILES["img"];
    $imgName=$img["name"];
    // $result = $connection->query("SELECT * FROM product WHERE name = '$name'");
    // $result = $db->getData("product" , "name = '$name'","*");
    $result = $db->getDataSpec("*","product", "name = '$name'");

    if ($result->num_rows > 0) {
        $errors['name'] = "**Product name already exists";
    }
    else if(strlen($name)<3){
        $errors['name']='**must be more than 3 chars';
    }
  
    if ($price <= 0) {
        $errors['price'] = "**price must be above 0";
    }
    if($img["size"]==0){
        $errors['img'] = "**Please upload an image";
    }
    if(count($errors)>0){
        $errors=json_encode($errors);
        header("location: productForm.php?errors=".$errors);
        
    }else{
    move_uploaded_file($img["tmp_name"],"../imgs/products/".$imgName);

    // $insert=$connection->prepare("INSERT INTO product (name,price,category_id,image) VALUES (?,?,?,?)");
    // $insert->execute([$name,$price,$category,$img['name']]);
    $db->insertInto("product", "(name,price,category_id,image)", "('$name','$price','$category','$imgName')");
    header("location: productTable.php?page=" . $_GET['page']);
    }
}catch (Exception $e) {
    var_dump($e->getMessage());
}

//close connection
function validate_data($data){
    $data = trim($data);
    $data=addslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}
// $connection->close();

?>