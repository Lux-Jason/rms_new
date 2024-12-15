<?php
session_start();
include 'connectdb.php'; // Assume this file contains the database connection

if (!isset($_SESSION['b_id'])) {
    die("Please login first.");
}
$b_id = $_SESSION['b_id'];

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Cart is empty.");
}
$cart = $_SESSION['cart'];

$totalAmount = 0;
foreach ($cart as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

// Apply discount if user is VIP
if (isset($_SESSION['type']) && $_SESSION['type'] == 'vip') {
    $discount = 0.10;
    $totalAmount *= (1 - $discount);
}

// Fetch buyer's current remains
$sql = "SELECT remains FROM buyer WHERE b_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $b_id);
$stmt->execute();
$stmt->bind_result($remains);
$stmt->fetch();
$stmt->close();

if ($remains < $totalAmount) {
    die("Insufficient balance.");
}

try {
    $conn->begin_transaction();

    $orderTime = date("Y-m-d H:i:s");
    $comment = ""; // Assuming no comment for now

    $sql = "INSERT INTO `order` (b_id, time, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $b_id, $orderTime, $comment);
    $stmt->execute();
    $order_id = $conn->insert_id;

    foreach ($cart as $item) {
        $dish_id = $item['dish_id'];
        $num_dishes = $item['quantity'];
        $price = $item['price'];
        $status = 'ordered';

        $sql = "INSERT INTO order_detail (order_id, dish_id, num_dishes, price, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiidc", $order_id, $dish_id, $num_dishes, $price, $status);
        $stmt->execute();
    }

    // Assuming a single seller for simplicity; in reality, this might vary per dish
    $s_id = 5; // Example seller ID

    $sql = "INSERT INTO operate (s_id, dish_id, order_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($cart as $item) {
        $dish_id = $item['dish_id'];
        $stmt->bind_param("iii", $s_id, $dish_id, $order_id);
        $stmt->execute();
    }

    $newRemains = $remains - $totalAmount;

    $sql = "UPDATE buyer SET remains = ? WHERE b_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $newRemains, $b_id);
    $stmt->execute();

    $conn->commit();

    unset($_SESSION['cart']);

    echo "Order submitted successfully. Order ID: " . $order_id;

} catch (Exception $e) {
    $conn->rollback();
    die("Error: " . $e->getMessage());
}
?>