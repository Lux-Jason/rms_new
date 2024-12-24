<?php
session_start();

$userType = $_SESSION['type'];

if (!isset($_SESSION['order_id'])) {
    die('Order ID not set in session.');
}
$order_id = $_SESSION['order_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'rms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch order details
$sql = "SELECT * FROM order_detail WHERE order_id = ? AND status = 'inprogress'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$orderDetails = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch dish names and images
foreach ($orderDetails as &$detail) {
    $dish_id = $detail['dish_id'];
    $sql = "SELECT dish_name, image FROM menu WHERE dish_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dish_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $menu = $result->fetch_assoc();
    $detail['dish_name'] = $menu['dish_name'];
    $detail['image'] = $menu['image'];
    $stmt->close();
}

// Output HTML for cart items
if (empty($orderDetails)) {
    echo '<p>Your cart is empty.</p>';
} else {
    foreach ($orderDetails as $detail) {
        $dishName = htmlspecialchars($detail['dish_name']);
        $numDishes = htmlspecialchars($detail['num_dishes']);
        $price = htmlspecialchars($detail['price']);
        $totalItemPrice = $numDishes * $price;

        if ($detail['image']) {
            $base64Image = base64_encode($detail['image']);
            $src = "data:image/jpeg;base64," . htmlspecialchars($base64Image);
        } else {
            $src = "nodish.jpg"; // Provide a default image
        }

        if ($userType == "vip") {
            echo '
            <div class="cart-item" data-quantity="' . $numDishes . '" data-price="' . $price . '">
                <div class="item-image">
                    <img src="' . $src . '" alt="' . $dishName . ' image">
                </div>
                <div class="item-details">
                    <div class="item-name">' . $dishName . '</div>
                    <div class="item-info">Discount: 10%&emsp;Quantity: ' . $numDishes . '&emsp;Price: $' . $price . '</div>
                    <div class="item-total">Total Price: $' . number_format($totalItemPrice, 2) . '</div>
                </div>
            </div>
            ';
        } else {
            echo '
            <div class="cart-item" data-quantity="' . $numDishes . '" data-price="' . $price . '">
                <div class="item-image">
                    <img src="' . $src . '" alt="' . $dishName . ' image">
                </div>
                <div class="item-details">
                    <div class="item-name">' . $dishName . '</div>
                    <div class="item-info">Discount: 0%&emsp;Quantity: ' . $numDishes . '&emsp;Price: $' . $price . '</div>
                    <div class="item-total">Total Price: $' . number_format($totalItemPrice, 2) . '</div>
                </div>
            </div>
            ';
        }
    }
}
?>