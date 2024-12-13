<?php
session_start();

include 'connectdb.php';

// Prepare SQL statement
$stmt = $conn->prepare("SELECT b_id, buyer_name, b_password, type, remains FROM buyer WHERE buyer_name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['b_password'])) {
        // Set session variables
        $_SESSION['username'] = $user['buyer_name'];
        $_SESSION['balance'] = $user['remains'];
        $_SESSION['last_activity'] = time();

        // Redirect based on user type
        header("Location: index.php");
        exit();
    } else {
        header("Location: login_pg.php?error=invalid_password");
        exit();
    }
} else {
    header("Location: login_pg.php?error=user_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>