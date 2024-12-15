<?php
include 'connectdb.php';

// 获取请求的参数
$dishId = isset($_POST['dish_id']) ? (int)$_POST['dish_id'] : 0;
$dishName = isset($_POST['dish_name']) ? $_POST['dish_name'] : '';
$dishPrice = isset($_POST['dish_price']) ? (float)$_POST['dish_price'] : 0.0;
$buyerId = isset($_POST['buyer_id']) ? (int)$_POST['buyer_id'] : 0;

// 1. 创建新的订单（如果尚未存在）
$sql = "INSERT INTO `order` (b_id, time) VALUES (:buyer_id, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':buyer_id', $buyerId, PDO::PARAM_INT);
$stmt->execute();

// 获取刚刚插入的订单 ID
$orderId = $conn->lastInsertId();

// 2. 在 `order_detail` 中插入菜品
$sqlDetail = "INSERT INTO `order_detail` (order_id, dish_id, num_dishes, price, status) 
              VALUES (:order_id, :dish_id, 1, :price, 'added')";
$stmtDetail = $conn->prepare($sqlDetail);
$stmtDetail->bindValue(':order_id', $orderId, PDO::PARAM_INT);
$stmtDetail->bindValue(':dish_id', $dishId, PDO::PARAM_INT);
$stmtDetail->bindValue(':price', $dishPrice, PDO::PARAM_STR);
$stmtDetail->execute();

// 返回响应
echo json_encode(['status' => 'success', 'order_id' => $orderId]);
