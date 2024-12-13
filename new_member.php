<?php
include "connectdb.php";

$user = $_POST["username"];
$pwd1 = $_POST["password"];
$pwd2 = $_POST["conf_password"];
$q = $_POST["security_question"];
$a = $_POST["answer"];

if ($pwd1 === $pwd2) {
    $sql = "INSERT INTO `buyer` (`b_id`, `buyer_name`, `b_password`, `type`, `remains`, `safety_question`, `answer`) 
            VALUES (NULL, :user, :pwd1, NULL, NULL, :q, :a)";

    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':user' => $user,
        ':pwd1' => $pwd1,
        ':q' => $q,
        ':a' => $a
    ]);

    if ($result) {
        echo "<script> alert('Registration Success. Redirecting you to login page.'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=login_pg.php'>";
    } else {
        echo "<script> alert('Registration Failed. Try again later.'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=registry_page.php'>";
    }
} else {
    echo "<script> alert('Mismatch between the two passwords entered!'); </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=registry_page.php'>";
}
?>