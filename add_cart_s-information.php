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
?>