<?php
session_start();
include "connectdb.php";

// 确保用户是管理员
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit();
}

if (isset($_POST['id'])) {
    $dish_id = $_POST['id'];

    try {
        // 准备 DELETE 查询
        $sql = "DELETE FROM menu WHERE dish_id = :dish_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dish_id', $dish_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Item deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No dish ID provided!";
}
?>
