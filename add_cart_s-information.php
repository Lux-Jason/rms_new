<?php
header('Content-Type: application/json');
// Assuming I've got the dishId via JavaScript and passed it to PHP via AJAX or some other means
$dishId = $_POST['dish_id']; // get dish_id

include 'connectdb.php';

try {
    // prepare statements for sql query
    $sql = "SELECT dish_name, price FROM menu WHERE dish_id = :dish_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);

    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Dish not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>