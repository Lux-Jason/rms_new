<?php
session_start(); 

header('Content-Type: application/json');

// Read the raw POST data
$input = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($input, true);

// Get the dish_id from the decoded data
if (isset($data['dish_id']) && !empty($data['dish_id'])) {
    $dishId = $data['dish_id']; // get dish_id

    // We use a single order id generated and stored in the session, and when the user logged out, the session is destroyed and the user cannot get access to this cart. 

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
            $username = $_SESSION['username']; 
            $type = $_SESSION['type']; 
            // get oid
            $order_id = $_SESSION['order_id']; 
            echo $order_id;

            $sqlUser = "SELECT b_id FROM buyer WHERE buyer_name = :username"; 
            $stmt = $conn->prepare($sqlUser);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR); 
            $stmt->execute(); 
            $userResult = $stmt->fetch(PDO::FETCH_ASSOC); 
            if (!$userResult) {
                echo json_encode(["error"=> "Cannot find user. "]);
                exit(); 
            }
            $userId = $userResult["b_id"]; 
            $price = $result['price']; 
            if ($type === 'vip') {
                $price *= 0.9; // vip user discount
            }
            $orderid = $order_id; 

            $sqlCheck = "SELECT num_dishes FROM order_detail WHERE order_id = :order_id AND dish_id = :dish_id"; 
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindParam(":order_id", $orderid, PDO::PARAM_INT); 
            $stmtCheck->bindParam(":dish_id", $dishId, PDO::PARAM_INT); 
            $stmtCheck->execute();
            $orderDetail = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if ($orderDetail) {
                $numDishes = $orderDetail["num_dishes"]+1;
                $sqlUpdate = "UPDATE order_detail SET num_dishes = :num_dishes, price = :price WHERE order_id = :order_id AND dish_id = :dish_id";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bindParam(":num_dishes", $numDishes, PDO::PARAM_INT); 
                $stmtUpdate->bindParam(":price", $price, PDO::PARAM_STR); 
                $stmtUpdate->bindParam(":order_id", $orderid, PDO::PARAM_INT); 
                $stmtUpdate->bindParam(":dish_id", $price, PDO::PARAM_INT); 
                $stmtUpdate->execute();
            } else {
                $numDishes = 1; 
                $sqlInsert = "INSERT INTO order_detail (order_id, dish_id, num_dishes, price, status) VALUES (:order_id, :dish_id, :num_dishes, :price, 'inprogress')";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bindParam(':order_id', $orderid, PDO::PARAM_INT);
                $stmtInsert->bindParam(':dish_id', $dishId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':num_dishes', $numDishes, PDO::PARAM_INT);
                $stmtInsert->bindParam(':price', $price, PDO::PARAM_STR);
                $stmtInsert->execute(); 
            }
            
        
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