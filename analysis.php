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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <div style="width: 20px;"></div>
            <a href="index.html"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()></a>
            <div style="width: 20px;"></div>
            <nav>
                <a href="index.html" class="button">Home</a>
                <a href="menu.html" class="button">All Dishes</a>
                <a href="reserve_tb.html" class="button">Reserve a table</a>
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

    <script>
        // Sample data for demonstration purposes
        const dailySalesData = {
            labels: ['2024-11-25', '2024-11-26', '2024-11-27', '2024-11-28', '2024-11-29', '2024-11-30', '2024-12-01'],
            datasets: [{
                label: 'Daily Sales ($)',
                data: [200, 300, 250, 400, 350, 450, 500],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const monthlySalesData = {
            labels: ['May', 'June', 'July', 'August', 'September', 'October', 'November'],
            datasets: [{
                label: 'Monthly Sales ($)',
                data: [1500, 2000, 2500, 3000, 3500, 4000, 4500],
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