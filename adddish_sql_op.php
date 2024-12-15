<?php

include "connectDB_localhost.php";  

$item	= $_POST["itemname"];
$inventory	= $_POST["inventory"];
$sp = $_POST["selling_price"]; //selling price.
$bp = $_POST["buying_price"]; //buying price.
$img_p = $_POST["image_path"]; //image path.

$sql = "INSERT INTO `inventory` (`id`, `item`, `number_we_have`, `selling_price`, `buying_price`, `image_path`) VALUES (NULL, '$item', '$inventory', '$sp', '$bp', '$img_p')";

$result = mysqli_query($conn, $sql);

$url = "Admin_pg.php";
echo "<script> alert('Successfully add item. '); </script>";
echo "<meta http-equiv='Refresh' content='0;URL=$url'>";
exit();	

?>
