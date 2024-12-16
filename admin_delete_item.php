<?php
session_start();
include "connectdb.php";

// Ensure user is an admin before processing
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit();
}

if (isset($_POST['id'])) {
    $dish_id = $_POST['id'];

    try {
        // Prepare the DELETE query
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
