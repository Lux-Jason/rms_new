<?php
$servername = "localhost";
$username = "root";	// change to your account's name
$password = "";	// change to your account's password
$db = "rms"; // change to the database name in stuweb.bcrab.cn
$port = '3306';
// Create connection
$conn = new mysqli($servername, $username, $password, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST['data'];

    foreach ($data as $row) {
        $id = $row[0];
        $dish_name = $row[1]; // Assuming the first column is the ID
        $ingredient = $row[2];
        $description = $row[3];
        $type = $row[4];
        $price = $row[5];
        $amount = $row[6];
        $note = $row[7];

        // $image = $row[8]

        $sql = "UPDATE menu SET dish_name='$dish_name', ingredient='$ingredient', description='$description', type='$type', price='$price', amount='$amount', note='$note' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        $sql_2 = "SELECT * FROM menu";
    }
    mysqli_close($conn);
}