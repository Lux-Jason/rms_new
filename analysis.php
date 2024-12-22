<?php
// 数据库连接
$host = "127.0.0.1";
$user = "root"; // 根据实际情况修改
$pass = ""; // 根据实际情况修改
$db = "rms"; // 数据库名称

// 创建数据库连接
$conn = new mysqli($host, $user, $pass, $db);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 获取日销售数据
$dailySalesQuery = "
    SELECT DATE(o.time) AS order_date, SUM(od.price * od.num_dishes) AS daily_sales
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    WHERE o.time >= CURDATE() - INTERVAL 7 DAY
    GROUP BY DATE(o.time)
    ORDER BY o.time DESC
";
$dailySalesResult = $conn->query($dailySalesQuery);
$dailyLabels = [];
$dailyData = [];
while ($row = $dailySalesResult->fetch_assoc()) {
    $dailyLabels[] = $row['order_date'];
    $dailyData[] = (float)$row['daily_sales'];
}

// 获取月销售数据
$monthlySalesQuery = "
    SELECT DATE_FORMAT(o.time, '%Y-%m') AS month, SUM(od.price * od.num_dishes) AS monthly_sales
    FROM `order` o
    JOIN `order_detail` od ON o.order_id = od.order_id
    WHERE o.time >= CURDATE() - INTERVAL 6 MONTH
    GROUP BY DATE_FORMAT(o.time, '%Y-%m')
    ORDER BY o.time DESC
";
$monthlySalesResult = $conn->query($monthlySalesQuery);
$monthlyLabels = [];
$monthlyData = [];
while ($row = $monthlySalesResult->fetch_assoc()) {
    $monthlyLabels[] = $row['month'];
    $monthlyData[] = (float)$row['monthly_sales'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Analysis</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .diagram {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script src="chart.js"></script>
</head>
<body>
<header>
    <div class="logo-container">
        <div style="width: 20px;"></div>
        <a href="index.php"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo"></a>
        <div style="width: 20px;"></div>
        <nav>
            <a href="index.php" class="button">Home</a>
            <a href="menu.php" class="button">All Dishes</a>
            <a href="reserve_tb.php" class="button">Reserve a table</a>
        </nav>
    </div>
    <div>
        <p>Welcome! Administrator. </p>
    </div>
</header>

<div id="reserved_area" style="height: 32px;"></div>

<div class="container">
    <h1>Sales Analysis</h1>
    <div class="diagram">
        <h2>Daily Sales</h2>
        <canvas id="dailySalesChart"></canvas>
    </div>
    <div class="diagram">
        <h2>Monthly Sales</h2>
        <canvas id="monthlySalesChart"></canvas>
    </div>
</div>

<div id="reserved_area" style="height: 32px;"></div>

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

<footer>
    <div class="footer-content">
        <p>&copy; 2024 Qiao's Handmade. All rights reserved</p>
        <a href="admin.php">Admin Page</a>
    </div>
</footer>

<script>
    // Chart data for daily sales (from PHP array)
    const dailySalesData = {
        labels: <?php echo json_encode($dailyLabels); ?>,
        datasets: [{
            label: 'Daily Sales ($)',
            data: <?php echo json_encode($dailyData); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    // Chart data for monthly sales (from PHP array)
    const monthlySalesData = {
        labels: <?php echo json_encode($monthlyLabels); ?>,
        datasets: [{
            label: 'Monthly Sales ($)',
            data: <?php echo json_encode($monthlyData); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };

    // Render the daily sales chart
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySalesChart = new Chart(dailySalesCtx, {
        type: 'line',
        data: dailySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Render the monthly sales chart
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const monthlySalesChart = new Chart(monthlySalesCtx, {
        type: 'bar',
        data: monthlySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
