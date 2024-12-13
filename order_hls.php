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
        <h1>Order History</h1>
        <div class="order">
            <img src="Kung Pao Shrimps.jpg" alt="Kung Pao Shrimps">
            <div>
                <p>Order Time: 2024-06-01 10:00</p>
                <p>Order Amount: $38</p>
                <a href="order_detail.php?id=1">View Details</a>
            </div>
        </div>
        <div class="order">
            <img src="Braised Pork.jpg" alt="Red Braised Pork">
            <div>
                <p>Order Time: 2024-05-15 12:30</p>
                <p>Order Amount: $48</p>
                <a href="order_detail.php?id=2">View Details</a>
            </div>
        </div>
        <!-- Example of expired order, which should not be displayed in theory -->
        <div class="order" style="display: none;">
            <p>Order Time: 2023-06-01 11:00</p>
            <p>Order Amount: $30</p>
            <a href="order_detail.php?id=3">View Details</a>
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