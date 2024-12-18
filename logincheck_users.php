<?php
session_start();
include 'connectdb.php'; // 确保此文件包含数据库连接

// 检查用户名和密码是否提供
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 准备SQL语句，按用户名查找用户
    $stmt = $conn->prepare("SELECT b_id, buyer_name, b_password, type, remains FROM buyer WHERE buyer_name = :username");
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // 获取结果
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // 使用 password_verify() 比较用户输入的密码与数据库中的哈希密码
        if (password_verify($password, $user['b_password'])) {
            // 设置会话变量
            $_SESSION['username'] = $user['buyer_name'];
            $_SESSION['type'] = $user['type']; // 用户类型
            $_SESSION['remains'] = $user['remains']; // 余额 (如果适用)
            $_SESSION['last_activity'] = time(); // 记录最后活动时间
            $min = 1000000000; 
            $max = 9999999999; 
            do {
                $oid = mt_rand($min, $max);
                $stmt = $conn->prepare("SELECT COUNT(*) FROM `order` WHERE order_id = :oid");
                $stmt->bindValue(':oid', $oid, PDO::PARAM_INT);
                $stmt->execute();
                $count = $stmt->fetchColumn();
            } while ($count > 0);
            
            $_SESSION['order_id'] = $oid;
            // 规避随机编码重复的可能情况

            // 获取当前时间
            $currentTime = date('Y-m-d H:i:s');

            // 插入新订单到数据库
            $stmt = $conn->prepare("INSERT INTO `order` (order_id, b_id, time, comment) VALUES (:order_id, :b_id, :time, :comment)");
            $stmt->bindValue(':order_id', $oid, PDO::PARAM_INT);
            $stmt->bindValue(':b_id', $user['b_id'], PDO::PARAM_INT);
            $stmt->bindValue(':time', $currentTime, PDO::PARAM_STR);
            $stmt->bindValue(':comment', '', PDO::PARAM_STR); // 如果没有评论，插入空字符串
            $stmt->execute();

            // 重定向到首页或用户的个人主页
            header("Location: index.php");
            exit();
        } else {
            // 密码错误
            header("Location: login_pg.php?error=invalid_password");
            exit();
        }
    } else {
        // 用户未找到
        header("Location: login_pg.php?error=user_not_found");
        exit();
    }
} else {
    // 未提供用户名或密码
    header("Location: login_pg.php?error=missing_credentials");
    exit();
}

$stmt = null;
$conn = null;
?>
