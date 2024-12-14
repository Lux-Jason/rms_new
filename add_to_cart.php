<?php
session_start();
include 'connectdb.php';  // Include the database connection

// Get the current buyer ID from session or use a default for testing
$buyer_id = $_SESSION['buyer_id'] ?? 1;
$data = json_decode(file_get_contents("php://input"), true);

$dish_id = $data['dishId'];
$quantity = $data['quantity'];

// Check if the dish is already in the cart
$query = "SELECT * FROM order_detail WHERE order_id = (SELECT order_id FROM `order` WHERE b_id = ?) AND dish_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $buyer_id, $dish_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the quantity if the dish is already in the cart
    $query = "UPDATE order_detail SET num_dishes = num_dishes + ? WHERE order_id = (SELECT order_id FROM `order` WHERE b_id = ?) AND dish_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $quantity, $buyer_id, $dish_id);
    $stmt->execute();
} else {
    // Insert the dish into the order_detail table if it's not already in the cart
    $query = "INSERT INTO order_detail (order_id, dish_id, num_dishes, price) SELECT order_id, ?, ?, price FROM `order` WHERE b_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $dish_id, $quantity, $buyer_id);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>
