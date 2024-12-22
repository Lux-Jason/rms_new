<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Include database connection
include "connectdb.php";

// Check if dish_id is passed
if (isset($_GET['dish_id'])) {
    $dishId = $_GET['dish_id'];

    // Prepare SQL query to fetch dish details
    $sql = "SELECT * FROM menu WHERE dish_id = :dish_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        $dish = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the dish exists
        if ($dish) {
            // Return the dish details as a JSON response
            echo json_encode([
                'status' => 'success',
                'data' => $dish
            ]);
        } else {
            // Dish not found
            echo json_encode([
                'status' => 'error',
                'message' => 'Dish not found'
            ]);
        }
    } else {
        // Query execution failed
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query failed'
        ]);
    }
} else {
    // No dish_id provided
    echo json_encode([
        'status' => 'error',
        'message' => 'No dish_id provided'
    ]);
}
?>
