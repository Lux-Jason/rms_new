<?php

include "connectDB_localhost.php";  

$item	= $_POST["itemname"];
$inventory	= $_POST["inventory"];
$sp = $_POST["selling_price"]; //selling price.
$bp = $_POST["buying_price"]; //buying price.
$img_p = $_POST["image_path"]; //image path.

$sql = "INSERT INTO `menu` (`dish_id`, `dish_name`, `ingredient`, `description`, `type`, `price`) VALUES (NULL, '$item', '$inventory', '$sp', '$bp', '$img_p')";

$result = mysqli_query($conn, $sql);

$url = "Admin_pg.php";
echo "<script> alert('Successfully add item. '); </script>";
echo "<meta http-equiv='Refresh' content='0;URL=$url'>";
exit();	

?>
