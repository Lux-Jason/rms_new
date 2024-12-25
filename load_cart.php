<?php
session_start();

$userType = $_SESSION['type'];

if (!isset($_SESSION['order_id'])) {
    die('Order ID not set in session.');
}

$order_id = $_SESSION['order_id'];

try {
    // Fetch order details with dish information in a single query
    $sql = "SELECT od.*, m.dish_name, m.image 
            FROM order_detail od 
            JOIN menu m ON od.dish_id = m.dish_id 
            WHERE od.order_id = :order_id AND od.status = 'inprogress'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Apply discount based on user type
    $discount = ($_SESSION['type'] === 'vip') ? 0.1 : 0;  // 10% discount for VIP users

    // Output HTML for cart items
    if (empty($orderDetails)) {
        echo '<p>Your cart is empty.</p>';
    } else {
        foreach ($orderDetails as $detail) {
            $dishName = htmlspecialchars($detail['dish_name']);
            $numDishes = htmlspecialchars($detail['num_dishes']);
            $price = htmlspecialchars($detail['price']);
            $totalItemPrice = $numDishes * $price * (1 - $discount);

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