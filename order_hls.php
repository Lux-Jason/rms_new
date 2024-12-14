<?php
// 禁用显示错误信息，记录错误到日志
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 启动会话
session_start();

// 检查用户是否登录
if (!isset($_SESSION['b_id'])) {
    echo '<p style="color: red; font-weight: bold;">Please log in to view your order history.</p>';
    // 你可以选择重定向用户到登录页面
    // header("Location: login.php");
    exit;  // 停止脚本执行，防止继续执行后续代码
}

$b_id = $_SESSION['b_id'];  // 获取登录的用户ID

// 连接数据库（根据你的设置）
require 'connectdb.php';

// 查询用户的订单历史
$sql = "
    SELECT o.order_id, o.time, o.comment, SUM(od.price * od.num_dishes) AS total_amount 
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    WHERE o.b_id = :b_id
    GROUP BY o.order_id, o.time, o.comment
    ORDER BY o.time DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute(['b_id' => $b_id]);

$orders = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $orders[] = $row;
}

$conn = null; // 关闭数据库连接
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .order {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .order img {
            max-width: 100px;
            margin-right: 20px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="logo-container">
        <div style="width: 20px;"></div>
        <a href="index.php"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()></a>
        <div style="width: 20px;"></div>
        <nav>
            <a href="index.php" class="button">Home</a>
            <a href="menu.php" class="button">All Dishes</a>
            <a href="reserve_tb.php" class="button">Reserve a table</a>
        </nav>
    </div>
    <div>
        <p>Welcome! Mr. John Doe.</p> <!-- 用户的登录信息可以动态显示 -->
    </div>
</header>

<div id="reserved_area" style="height: 32px;"></div>

<div class="container">
    <h1>Order History</h1>

    <?php
    // 显示订单历史
    if (!empty($orders)) {
        foreach ($orders as $order) {
            echo '<div class="order">';
            echo '<img src="Kung Pao Shrimps.jpg" alt="Kung Pao Shrimps">';
            echo '<div>';
            echo '<p>Order Time: ' . $order['time'] . '</p>';
            echo '<p>Order Amount: $' . $order['total_amount'] . '</p>';
            echo '<a href="order_detail.php?id=' . $order['order_id'] . '">View Details</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No order history available.</p>';
    }
    ?>
</div>

<!-- Other Information -->
<section class="other-information">
    <h2>About us</h2>
    <div class="info-container">
        <div class="info-box">
            <h3>Running Time: </h3>
            <p>Monday to Saturday<br>10:00 - 22:00</p>
        </div>
        <div class="info-box">
            <h3>Contact us: </h3>
            <p>Telephone: 19935820001</p>
            <p>E-mail: info@qiaohandmade.com</p>
        </div>
        <div class="info-box">
            <h3>Address: </h3>
            <p>BNU-UIC 2nd D5B-A111</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <p>&copy; 2024 Qiao's Handmade. All rights reserved</p>
        <a href="admin.html">Admin Page</a>
    </div>
</footer>

</body>
</html>
