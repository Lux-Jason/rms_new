<?php
session_start();
include "connectdb.php";

// Ensure user is an admin before processing
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit();
}

if (isset($_POST['dish_id']) && isset($_POST['dish_name']) && isset($_POST['ingredient']) && isset($_POST['description']) && isset($_POST['type']) && isset($_POST['amount']) && isset($_POST['note'])) {
    $dish_id = $_POST['dish_id'];
    $dish_name = $_POST['dish_name'];
    $ingredient = $_POST['ingredient'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $note = $_POST['note'];

    // Handle image upload
    $image = null;  // Default is no image update
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Get image file details
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageType = $_FILES['image']['type'];

        // Read image file content
        $imageData = file_get_contents($imageTmpName);
        $image = $imageData; // Store the binary image data
    }

    try {
        // Prepare the UPDATE query
        $sql = "UPDATE menu SET dish_name = :dish_name, ingredient = :ingredient, description = :description, type = :type, amount = :amount, note = :note";

        // If there's an image, add it to the query
        if ($image !== null) {
            $sql .= ", image = :image";
        }

        $sql .= " WHERE dish_id = :dish_id";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind values
        $stmt->bindParam(':dish_name', $dish_name);
        $stmt->bindParam(':ingredient', $ingredient);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':dish_id', $dish_id, PDO::PARAM_INT);

        // Bind image if it's being updated
        if ($image !== null) {
            $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        }

        // Execute the query
        $stmt->execute();

        echo "Item updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Incomplete data!";
}
?>
