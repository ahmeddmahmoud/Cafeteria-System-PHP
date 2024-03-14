<?php
//open connection
$connection = new mysqli("localhost", "php", "1234", "cafe");

if ($connection->connect_errno) {
    die("Connection failed...");
    
}
//query
try {
    $name = validate_data($_POST['productname']);
    $price = validate_data($_POST['price']);
    $category =validate_data($_POST['category']);
    $errors=[];
    $img=$_FILES["img"];
    if(strlen($name)<2){
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
    move_uploaded_file($img["tmp_name"],"../imgs/products/".$img["name"]);

    $insert=$connection->prepare("INSERT INTO product (name,price,category_id,image) VALUES (?,?,?,?)");
    $insert->execute([$name,$price,$category,$img['name']]);
    header("location: productTable.php");
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
$connection->close();

?>