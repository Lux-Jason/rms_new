<?php
session_start();
include "connectdb.php";

// Ensure user is an admin before processing
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit();
}

if (isset($_POST['data'])) {
    $tableData = $_POST['data']; // This is the data array from the frontend

    try {
        // Iterate through the table data to update each item
        foreach ($tableData as $rowData) {
            // Assuming each row contains: dish_id, dish_name, ingredient, description, type, amount, note
            $dish_id = $rowData[0];
            $dish_name = $rowData[1];
            $ingredient = $rowData[2];
            $description = $rowData[3];
            $type = $rowData[4];
            $amount = $rowData[5];
            $note = $rowData[6];

            // Prepare the UPDATE query
            $sql = "UPDATE menu SET dish_name = :dish_name, ingredient = :ingredient, description = :description, type = :type, amount = :amount, note = :note WHERE dish_id = :dish_id";
            $stmt = $conn->prepare($sql);

            // Bind values
            $stmt->bindParam(':dish_name', $dish_name);
            $stmt->bindParam(':ingredient', $ingredient);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':dish_id', $dish_id, PDO::PARAM_INT);

            // Execute query
            $stmt->execute();
        }

        echo "All items updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No data provided!";
}
?>
