<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "rms", "3316");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get login form data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT b_id, buyer_name, b_password FROM buyer WHERE buyer_name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password (assuming passwords are hashed in database)
    if (password_verify($password, $user['b_password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['b_id'];
        $_SESSION['username'] = $user['buyer_name'];

        // Redirect to user dashboard
        header("Location: user_dashboard.php");
        exit();
    } else {
        // Wrong password
        header("Location: login_pg.php?error=invalid_password");
        exit();
    }
} else {
    // User not found
    header("Location: login_pg.php?error=user_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>