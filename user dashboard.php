<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .info {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
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
            <p>Welcome! Mr. John Doe. </p>

        </div>
    </header>

    <div id="reserved_area" style="height: 32px;"></div>

    <div class="container">
        <h1>User Dashboard</h1>
        <div class="info">
            <h2>Personal Information</h2>
            <p>Name: John Doe</p>
            <p>Email: johndoe@example.com</p>
            <p>Phone: 12345678901</p>
            <a href="personal_info.php">Edit Personal Information</a>
        </div>
        <div class="info">
            <h2>Order Information</h2>
            <a href="order_hls.php">View Order History</a>
        </div>
        <div class="info">
            <h2>VIP Status</h2>
            <p>VIP Level: Silver VIP</p>
            <p>Account Balance: $200</p>
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
</body>
</html>