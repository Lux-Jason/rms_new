<?php
session_start();
include "connectdb.php";

// 确保用户是管理员
if (!isset($_SESSION['username'])) {
    echo "You are not logged in.";
    exit();
}

if (isset($_POST['dish_id']) && isset($_POST['dish_name']) && isset($_POST['ingredient']) && isset($_POST['description']) && isset($_POST['type']) && isset($_POST['amount']) && isset($_POST['note'])) {
    $dish_id = $_POST['dish_id'];
    $dish_name = $_POST['dish_name'];
    $ingredient = $_POST['ingredient'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $note = $_POST['note'];

    // 处理图片上传
    $image = null;  // 默认不更新图片
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // 获取图像文件详情
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageType = $_FILES['image']['type'];

        // 读取图像文件内容
        $imageData = file_get_contents($imageTmpName);
        $image = $imageData; // 存储二进制图像数据
    }

    try {
        // 准备 UPDATE 查询
        $sql = "UPDATE menu SET dish_name = :dish_name, ingredient = :ingredient, description = :description, type = :type, amount = :amount, note = :note";

        // 如果有图像，则添加图像字段
        if ($image !== null) {
            $sql .= ", image = :image";
        }

        $sql .= " WHERE dish_id = :dish_id";

        // 准备语句
        $stmt = $conn->prepare($sql);

        // 绑定值
        $stmt->bindParam(':dish_name', $dish_name);
        $stmt->bindParam(':ingredient', $ingredient);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':dish_id', $dish_id, PDO::PARAM_INT);

        // 如果有图像，绑定图像字段
        if ($image !== null) {
            $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        }

        // 执行查询
        $stmt->execute();

        echo "Item updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Incomplete data!";
}
?>
