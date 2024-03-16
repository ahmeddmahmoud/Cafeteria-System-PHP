<?php
include_once '../db.php'; // Include the DB class file
$id = $_GET['id'];
$page=$_GET['page'];
//open connection

$db=new DB();
$db->__construct();
require_once '../functions/validateSourcePage.php';
if (!isset($_POST['productname'])) {
    validateSourcePage('editProduct.php', '../errors/err.php',403);
}
//query
try {
    $nameInput = validate_data($_POST['productname']);
    $name=ucwords($nameInput);
    $price = validate_data($_POST['price']);
    $category =validate_data($_POST['category']);
    $errors=[];
    $img=$_FILES["img"];
    $imgName=$img["name"];
    
    $result = $db->getDataSpec("*","product", "name = '$name' AND id != '$id'","*");
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
        header("location: editProduct.php?id=$id&errors=".$errors);
        
    }else{
    move_uploaded_file($img["tmp_name"],"../imgs/products/".$imgName);

    // $update=$connection->query("UPDATE product SET name = '$name', price = '$price', category_id = '$category', image = '$img[name]' WHERE id = '$id'");
    $db->updateData("product" , "name = '$name', price = '$price', category_id = '$category', image = '$imgName'" ,"id = '$id'");
    header("location: productTable.php?page=".$page);

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