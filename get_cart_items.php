<?php
require 'connectdb.php';
session_start();

$userId = $_SESSION['user_id']; // Get the current user ID from session

// Fetch user info to check membership
$query = "SELECT type FROM buyer WHERE b_id = $userId";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Check if user is a VIP member
$isMember = $user['type'] == 'VIP';  // VIP check for the user

// Fetch the items in the cart for this user
$query = "SELECT menu.dish_id, menu.dish_name, menu.price, menu.image, order_detail.num_dishes
          FROM menu
          JOIN order_detail ON menu.dish_id = order_detail.dish_id
          JOIN `order` ON order_detail.order_id = `order`.order_id
          WHERE order_detail.b_id = $userId AND order_detail.status = 'in_cart'"; // Assuming 'in_cart' status
$result = mysqli_query($conn, $query);

$cartItems = [];
while ($item = mysqli_fetch_assoc($result)) {
    // Prepare cart item details
    $priceAfterDiscount = $isMember ? $item['price'] * 0.9 : $item['price'];  // Apply 10% discount for members
    $cartItems[] = [
        'dish_id' => $item['dish_id'],
        'dish_name' => $item['dish_name'],
        'price' => $priceAfterDiscount,
        'image' => base64_encode($item['image']), // If necessary to return image in base64
        'quantity' => $item['num_dishes'],
        'isMember' => $isMember
    ];
}

// Return the cart items along with the membership status
echo json_encode([
    'items' => $cartItems,
    'isMember' => $isMember
]);
?>
