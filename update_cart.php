<?php
// Assuming you already have a database connection $conn
$data = json_decode(file_get_contents('php://input'), true);
$dishId = $data['dishId'];
$quantity = $data['quantity'];

session_start();
$userId = $_SESSION['user_id']; // Get the current user ID from session

// Update the quantity in the cart for this user
$query = "UPDATE order_detail 
          SET num_dishes = $quantity
          WHERE b_id = $userId AND dish_id = $dishId AND status = 'in_cart'"; // Assuming 'in_cart' status

if (mysqli_query($conn, $query)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
?>
