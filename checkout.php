<?php
include 'connectdb.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
        $order_id = (int)$_POST['order_id'];

        try {
            // Check if the order exists
            $stmt = $conn->prepare("SELECT 1 FROM `order` WHERE order_id = :order_id");
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                // Order exists, proceed to update order_detail
                $updateStmt = $conn->prepare("UPDATE order_detail SET status = 'ordered' WHERE order_id = :order_id AND status = 'inprogress'");
                $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $updateStmt->execute();
                if ($updateStmt->rowCount() > 0) {
                    // Successfully updated
                    echo json_encode(array('status' => 'success', 'message' => 'Order status updated to ordered.'));
                } else {
                    // No rows updated, possibly already ordered
                    echo json_encode(array('status' => 'error', 'message' => 'No changes made. Order may already be ordered or not in progress.'));
                }
            } else {
                // Order does not exist
                echo json_encode(array('status' => 'error', 'message' => 'Invalid order_id.'));
            }
        } catch(PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage()));
        }
    } else {
        // Invalid or missing order_id
        echo json_encode(array('status' => 'error', 'message' => 'Invalid or missing order_id.'));
    }
} else {
    // Invalid request method
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>