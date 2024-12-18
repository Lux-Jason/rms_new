<?php
// Disable displaying error messages and log errors
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

$s_id = $_SESSION['s_id'];  // Get the current seller's ID

// Connect to the database (according to your settings)
require 'connectdb.php';

// Query the seller's transaction history, including dish name, quantity, and total amount
$sql = "
    SELECT o.order_id, o.time, o.b_id, 
           GROUP_CONCAT(d.dish_name ORDER BY od.dish_id) AS dish_names, 
           GROUP_CONCAT(od.num_dishes ORDER BY od.dish_id) AS quantities, 
           SUM(od.price * od.num_dishes) AS total_amount
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    JOIN `menu` d ON od.dish_id = d.dish_id
    JOIN `operate` op ON o.order_id = op.order_id
    WHERE op.s_id = :s_id
    GROUP BY o.order_id, o.time, o.b_id
    ORDER BY o.time DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute(['s_id' => $s_id]);  // Execute the query using the current seller's ID

$orders = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $orders[] = $row;  // Save the query results into an array
}

$conn = null; // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Transaction History</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .order {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .order-details {
            margin-top: 10px;
        }
        .dish {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="logo-container">
        <div style="width: 20px;"></div>
        <a href="index.php"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo"></a>
        <div style="width: 20px;"></div>
        <nav>
            <a href="index.php" class="button">Home</a>
            <a href="menu.php" class="button">All Dishes</a>
            <a href="reserve_tb.php" class="button">Reserve a table</a>
        </nav>
    </div>
    <div>
        <p>Welcome, Seller! </p> <!-- Display the seller's name or ID -->
    </div>
</header>

<div id="reserved_area" style="height: 32px;"></div>

<div class="container">
    <h1>Seller Transaction History</h1>

    <?php
    // Display transaction history
    if (!empty($orders)) {
        $current_order_id = null;
        $order_details = [];

        foreach ($orders as $order) {
            // If it's a new order, output the previous order's information
            if ($current_order_id !== $order['order_id']) {
                if ($current_order_id !== null) {
                    // Display order details
                    echo '<div class="order">';
                    echo '<p><strong>Order Time:</strong> ' . $order['time'] . '</p>';
                    echo '<p><strong>Buyer ID:</strong> ' . $order['b_id'] . '</p>';
                    echo '<p><strong>Transaction Amount:</strong> $' . number_format($total_amount, 2) . '</p>';
                    echo '<div class="order-details">';
                    foreach ($order_details as $dish) {
                        echo '<div class="dish"><strong>' . $dish['dish_name'] . ':</strong> ' . $dish['num_dishes'] . ' x $' . number_format($dish['price'], 2) . '</div>';
                    }
                    echo '</div>';
                    echo '<a href="order_detail.php?id=' . $current_order_id . '">View Details</a>';
                    echo '</div>';
                }

                // Update the current order ID and order information
                $current_order_id = $order['order_id'];
                $order_details = [];
                $total_amount = 0;  // Reset the total transaction amount
            }

            // Add dish and amount to the current order
            $order_details[] = [
                'dish_name' => $order['dish_name'],
                'num_dishes' => $order['num_dishes'],
                'price' => $order['price']
            ];
            $total_amount += $order['total_amount'];  // Accumulate the amount
        }

        // Output the last order's information
        if ($current_order_id !== null) {
            echo '<div class="order">';
            echo '<p><strong>Order Time:</strong> ' . $order['time'] . '</p>';
            echo '<p><strong>Buyer ID:</strong> ' . $order['b_id'] . '</p>';
            echo '<p><strong>Transaction Amount:</strong> $' . number_format($total_amount, 2) . '</p>';
            echo '<div class="order-details">';
            foreach ($order_details as $dish) {
                echo '<div class="dish"><strong>' . $dish['dish_name'] . ':</strong> ' . $dish['num_dishes'] . ' x $' . number_format($dish['price'], 2) . '</div>';
            }
            echo '</div>';
            echo '<a href="order_detail.php?id=' . $current_order_id . '">View Details</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No transactions found.</p>';
    }
    ?>
</div>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <p>&copy; 2024 Qiao's Handmade. All rights reserved</p>
        <a href="admin.php">Admin Page</a>
    </div>
</footer>

</body>
</html>
