<?php
include 'connectdb.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM menu WHERE dish_id = :dish_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':dish_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo "Item deleted successfully!";
} else {
    echo "No ID received!";
}
?>
