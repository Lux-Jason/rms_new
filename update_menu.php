<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Dish</title>
</head>
<body>
<h2>Upload Dish</h2>

<?php
// 判断是否是表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'connectdb.php';
    try {
        // 获取表单数据，确保如果没有提交数据则为 null
        $dish_name = isset($_POST['dish_name']) ? $_POST['dish_name'] : null;
        $ingredient = isset($_POST['ingredient']) ? $_POST['ingredient'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $type = isset($_POST['type']) ? $_POST['type'] : null;
        $price = isset($_POST['price']) ? $_POST['price'] : null;
        $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
        $note = isset($_POST['note']) ? $_POST['note'] : null;

        // 处理上传的图片
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_data = file_get_contents($_FILES['image']['tmp_name']);
            if ($image_data === false) {
                die("Failed to read image file.");
            }
        } else {
            $image_data = null;
        }

        // 检查 dish_name, price, and amount are not null
        if ($dish_name === null || $price === null || $amount === null) {
            die("Error: dish_name, price, and amount are required.");
        }

        // 检查菜品名称是否已存在
        $stmt = $conn->prepare("SELECT COUNT(*) FROM menu WHERE dish_name = :dish_name");
        $stmt->bindParam(':dish_name', $dish_name);
        $stmt->execute();
        $dish_count = $stmt->fetchColumn();

        if ($dish_count > 0) {
            echo "<p style='color:red;'>Error: A dish with this name already exists.</p>";
        } else {
            // 准备 SQL 语句
            $stmt = $conn->prepare("INSERT INTO menu (dish_name, ingredient, description, type, price, amount, note, image) 
                                   VALUES (:dish_name, :ingredient, :description, :type, :price, :amount, :note, :image)");

            // 绑定参数
            $stmt->bindParam(':dish_name', $dish_name);
            $stmt->bindParam(':ingredient', $ingredient);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':image', $image_data, PDO::PARAM_LOB);

            // 执行 SQL 语句
            if ($stmt->execute()) {
                // 成功插入数据后重定向到菜品更新页面
                header("Location: updatemenu.php");
                exit();
            } else {
                echo "<p style='color:red;'>Error: " . $stmt->errorInfo()[2] . "</p>";
            }
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>

<!-- HTML 表单 -->
<form action="updatemenu.php" method="post" enctype="multipart/form-data">
    <label for="dish_name">Dish Name:</label>
    <input type="text" id="dish_name" name="dish_name" required><br><br>

    <label for="ingredient">Ingredient:</label>
    <textarea id="ingredient" name="ingredient"></textarea><br><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea><br><br>

    <label for="type">Type:</label>
    <input type="text" id="type" name="type"><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" required><br><br>

    <label for="note">Note:</label>
    <textarea id="note" name="note"></textarea><br><br>

    <label for="image">Image:</label>
    <input type="file" id="image" name="image" required><br><br>

    <input type="submit" value="Upload">
</form>

</body>
</html>
