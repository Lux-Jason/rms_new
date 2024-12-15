<?php
// Include the database connection
require 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];  // New password
    $conf_password = $_POST['conf_password'];  // Confirm new password

    // Get security question answers
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

    try {
        // Start the transaction
        $conn->beginTransaction();

        // Step 1: Get buyer ID from the username
        $stmt = $conn->prepare("SELECT b_id FROM buyer WHERE buyer_name = ?");
        $stmt->execute([$username]);
        $buyer = $stmt->fetch();

        if (!$buyer) {
            echo "User not found.";
            exit;
        }

        $b_id = $buyer['b_id'];

        // Step 2: Retrieve the existing security question and hashed answers
        $stmt_security = $conn->prepare("SELECT security_question, answer_hash FROM security WHERE user_id = ?");
        $stmt_security->execute([$b_id]);
        $security_data = $stmt_security->fetchAll(PDO::FETCH_ASSOC);

        // Step 3: Validate answers for each question (if set)
        $valid = true;

        foreach ($security_data as $index => $data) {
            // Depending on the question, validate the corresponding answer
            $answer_field = "answer" . ($index + 1);
            $security_question = $data['security_question'];
            $answer = $$answer_field;  // Dynamic variable

            if (!password_verify($answer, $data['answer_hash'])) {
                echo "Incorrect answer to security question: " . $security_question;
                $valid = false;
                break;
            }
        }

        if (!$valid) {
            exit;  // If validation failed, stop the process
        }

        // Step 4: Hash the new password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Step 5: Update the password in the buyer table
        $stmt_update_pwd = $conn->prepare("UPDATE buyer SET b_password = ? WHERE b_id = ?");
        $stmt_update_pwd->execute([$hashed_password, $b_id]);

        // Step 6: Update the security answers (if any new ones are provided)
        if ($security_question1 && $answer1) {
            $answer_hash1 = password_hash($answer1, PASSWORD_BCRYPT);
            $stmt_update_security1 = $conn->prepare("UPDATE security SET answer_hash = ? WHERE user_id = ? AND security_question = ?");
            $stmt_update_security1->execute([$answer_hash1, $b_id, $security_question1]);
        }

        if ($security_question2 && $answer2) {
            $answer_hash2 = password_hash($answer2, PASSWORD_BCRYPT);
            $stmt_update_security2 = $conn->prepare("UPDATE security SET answer_hash = ? WHERE user_id = ? AND security_question = ?");
            $stmt_update_security2->execute([$answer_hash2, $b_id, $security_question2]);
        }

        if ($security_question3 && $answer3) {
            $answer_hash3 = password_hash($answer3, PASSWORD_BCRYPT);
            $stmt_update_security3 = $conn->prepare("UPDATE security SET answer_hash = ? WHERE user_id = ? AND security_question = ?");
            $stmt_update_security3->execute([$answer_hash3, $b_id, $security_question3]);
        }

        // Commit the transaction
        $conn->commit();

        echo "Password reset successful!";
        // Redirect to login page
        header("Location: login_pg.php");
        exit;

    } catch (PDOException $e) {
        // Rollback transaction if there is any error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn = null;
}

?>
