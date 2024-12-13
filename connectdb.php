<?php
// Database connection parameters
$host = "localhost"; // Change if your database is on a different host
$username = "root";
$port = 3306;
$db = "rms"; // Make sure to wrap the database name in quotes
$pwd = "";

try {
    // Create a PDO connection
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $pwd);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>