<?php

header('Content-Type: application/json');

// Read the raw POST data
$input = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($input, true);

// Get the dish_id from the decoded data
if (isset($data['dish_id']) && !empty($data['dish_id'])) {
    $dishId = $data['dish_id']; // get dish_id

    // Validate the dish_id to ensure it's a positive integer
    if (!filter_var($dishId, FILTER_VALIDATE_INT) || $dishId <= 0) {
        echo json_encode(['error' => 'Invalid dish ID']);
        exit;
    }

    // Include database connection
    include 'connectdb.php';

    try {
        // Prepare the SQL query
        $sql = "SELECT dish_name, price FROM menu WHERE dish_id = :dish_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Return the result as JSON
            echo json_encode($result);
        } else {
            // If no dish is found, return an error message
            echo json_encode(['error' => 'Dish not found']);
        }
    } catch (PDOException $e) {
        // Handle database errors and return a JSON response
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If dish_id is not set or is empty
    echo json_encode(['error' => 'Dish ID is required']);
}


// ^Old function to implement the function of transfering data. 

// New function to implement the shopping cart adding function.

/*
header('Content-Type: application/json');

// Read the raw POST data
$input = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($input, true);

// Get the dish_id from the decoded data
if (isset($data['dish_id']) && !empty($data['dish_id'])) {
    $dishId = $data['dish_id']; // get dish_id

    // Validate the dish_id to ensure it's a positive integer
    if (!filter_var($dishId, FILTER_VALIDATE_INT) || $dishId <= 0) {
        echo json_encode(['error' => 'Invalid dish ID']);
        exit;
    }

    // Ensure session is started
    session_start();

    // Include database connection
    include 'connectdb.php';

    try {
        // Prepare the SQL query to get dish information
        $sql = "SELECT dish_name, price FROM menu WHERE dish_id = :dish_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Generate a random 8-digit order ID
            if (!isset($_SESSION['order_id'])) {
                $_SESSION['order_id'] = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            } else {
                echo json_encode(['error' => 'Order already exists']);
                exit;
            }

            // Get user information from session
            $username = $_SESSION["username"];
            $type = $_SESSION['type'];

            // Get user ID from database
            $sqlUser = "SELECT b_id FROM buyers WHERE username = :username";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->bindParam(':username', $username, PDO::PARAM_STR);
            $stmtUser->execute();
            $userResult = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if (!$userResult) {
                echo json_encode(['error' => 'User not found']);
                exit;
            }

            $userId = $userResult['b_id'];

            // Get the current time
            $currentTime = date('Y-m-d H:i:s');

            // Check if the user is a member
            $memberDiscount = 1.0;
            if ($type === 'member') {
                $memberDiscount = 0.9;
            }

            // Calculate the price
            $price = $result['price'] * $memberDiscount;

            // Insert the order into the order table
            $sqlOrder = "INSERT INTO order (b_id, time, status) VALUES (:b_id, :time, :status)";
            $stmtOrder = $conn->prepare($sqlOrder);
            $stmtOrder->bindParam(':b_id', $userId, PDO::PARAM_INT);
            $stmtOrder->bindParam(':time', $currentTime, PDO::PARAM_STR);
            $stmtOrder->bindParam(':status', 'inprogress', PDO::PARAM_STR);
            $stmtOrder->execute();

            // Get the order ID from the session
            $orderId = $_SESSION['order_id'];

            // Insert the order detail into the order_detail table
            $sqlOrderDetail = "INSERT INTO order_detail (order_id, dish_id, num_dishes, price, status) VALUES (:order_id, :dish_id, :num_dishes, :price, :status)";
            $stmtOrderDetail = $conn->prepare($sqlOrderDetail);
            $stmtOrderDetail->bindParam(':order_id', $orderId, PDO::PARAM_STR);
            $stmtOrderDetail->bindParam(':dish_id', $dishId, PDO::PARAM_INT);
            $stmtOrderDetail->bindParam(':num_dishes', 1, PDO::PARAM_INT);
            $stmtOrderDetail->bindParam(':price', $price, PDO::PARAM_INT);
            $stmtOrderDetail->bindParam(':status', 'inprogress', PDO::PARAM_STR);
            $stmtOrderDetail->execute();

            // Return the result as JSON
            echo json_encode($result);
        } else {
            // If no dish is found, return an error message
            echo json_encode(['error' => 'Dish not found']);
        }
    } catch (PDOException $e) {
        // Handle database errors and return a JSON response
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If dish_id is not set or is empty
    echo json_encode(['error' => 'Dish ID is required']);
}
*/
?>