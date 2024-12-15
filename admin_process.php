<?php
include 'connectdb.php';

if (isset($_POST['data'])) {
    $data = $_POST['data'];

    foreach ($data as $row) {
        $sql = "UPDATE menu SET dish_name = :dish_name, ingredient = :ingredient, description = :discription, type = :type, amount = :amount, note = :note WHERE dish_id = :dish_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':dish_name', $row[1], PDO::PARAM_STR);
        $stmt->bindValue(':ingredient', $row[2], PDO::PARAM_STR);
        $stmt->bindValue(':discription', $row[3], PDO::PARAM_STR);
        $stmt->bindValue(':type', $row[4], PDO::PARAM_STR);
        $stmt->bindValue(':amount', $row[5], PDO::PARAM_INT);
        $stmt->bindValue(':note', $row[6], PDO::PARAM_STR);
        $stmt->bindValue(':dish_id', $row[0], PDO::PARAM_INT);
        $stmt->execute();
    }

    echo "Update successful!";
} else {
    echo "No data received!";
}
?>
