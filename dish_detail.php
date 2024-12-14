<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dish Details</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .dish {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            align-items: center;
            width: 100%;
        }
        .dish img {
            margin-right: 20px;
            margin-left: 20px;
            vertical-align: middle;
            height: auto;
        }
        .no-dish {
            color: red;
            font-size: 18px;
            margin-top: 20px;
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
        <button class="button login-btn" onclick="loginNotice()">Login</button>
        <a href="#" class="button register-btn">register</a>
    </div>
</header>

<div id="reserved_area" style="height: 32px;"></div>

<div class="container">
    <h1>Dish Details</h1>

    <?php
    // 连接数据库
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rms";

    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 检查连接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 获取菜品的ID
    if (isset($_GET['id'])) {
        $dish_id = $_GET['id'];

        // 查询菜品的详细信息
        $sql = "SELECT dish_name, ingredient, description, price, image FROM menu WHERE dish_id = $dish_id";
        $result = $conn->query($sql);

        // 获取数据
        if ($result->num_rows > 0) {
            $dish = $result->fetch_assoc();
            echo '<div class="dish">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($dish['image']) . '" alt="' . htmlspecialchars($dish['dish_name']) . '" style="width: 400px;">';
            echo '<div>';
            echo '<h2>' . htmlspecialchars($dish['dish_name']) . '</h2>';
            echo '<p><strong>Ingredients:</strong> ' . htmlspecialchars($dish['ingredient']) . '</p>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($dish['description']) . '</p>';
            echo '<p><strong>Price:</strong> $' . number_format($dish['price'], 2) . '</p>';
            echo '<button class="add-to-cart-button">Add to Cart</button>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<p class="no-dish">No dish selected or dish not found.</p>';
        }
    } else {
        // 没有传递id时显示 "No dish selected"
        echo '<p class="no-dish">No dish selected.</p>';
    }

    // 关闭连接
    $conn->close();
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
