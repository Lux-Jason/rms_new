<?php
// Include the database connection
require 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $buyer_name = $_POST['username'];
    $password = $_POST['password'];  // Original password
    $conf_password = $_POST['conf_password'];  // Confirm password

    // Get security questions and answers
    $security_question1 = $_POST['security_question1'];
    $security_question2 = isset($_POST['security_question2']) ? $_POST['security_question2'] : null;
    $security_question3 = isset($_POST['security_question3']) ? $_POST['security_question3'] : null;

    $answer1 = $_POST['answer1'];
    $answer2 = isset($_POST['answer2']) ? $_POST['answer2'] : null;
    $answer3 = isset($_POST['answer3']) ? $_POST['answer3'] : null;

    // Check if passwords match
    if ($password !== $conf_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Begin transaction for all queries
        $conn->beginTransaction();

        // Insert new buyer (default type = 'normal')
        $stmt_buyer = $conn->prepare("INSERT INTO buyer (buyer_name, b_password, type) VALUES (?, ?, 'normal')");
        $stmt_buyer->execute([$buyer_name, $hashed_password]);

        // Get the last inserted buyer ID
        $b_id = $conn->lastInsertId();

        // Hash answers to security questions
        $answer_hash1 = password_hash($answer1, PASSWORD_BCRYPT);
        $answer_hash2 = $security_question2 ? password_hash($answer2, PASSWORD_BCRYPT) : null;
        $answer_hash3 = $security_question3 ? password_hash($answer3, PASSWORD_BCRYPT) : null;

        // Insert security question answers (only insert if question and answer are provided)
        if ($security_question1 && $answer1) {
            $stmt_security1 = $conn->prepare("INSERT INTO security (user_id, security_question, answer_hash) VALUES (?, ?, ?)");
            $stmt_security1->execute([$b_id, $security_question1, $answer_hash1]);
        }

        if ($security_question2 && $answer2) {
            $stmt_security2 = $conn->prepare("INSERT INTO security (user_id, security_question, answer_hash) VALUES (?, ?, ?)");
            $stmt_security2->execute([$b_id, $security_question2, $answer_hash2]);
        }

        if ($security_question3 && $answer3) {
            $stmt_security3 = $conn->prepare("INSERT INTO security (user_id, security_question, answer_hash) VALUES (?, ?, ?)");
            $stmt_security3->execute([$b_id, $security_question3, $answer_hash3]);
        }

        // Commit the transaction
        $conn->commit();

        // Check if the insertions were successful
        if ($stmt_buyer->rowCount() > 0 && ($security_question1 && $stmt_security1->rowCount() > 0) ||
            ($security_question2 && $stmt_security2->rowCount() > 0) ||
            ($security_question3 && $stmt_security3->rowCount() > 0)) {
            echo "Registration successful!";
            // Redirect to login page
            header("Location: login_pg.php");
            exit;
        } else {
            echo "Registration failed. Please try again.";
        }

    } catch (PDOException $e) {
        // If there is any error, roll back the transaction
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn = null;
}
?>
