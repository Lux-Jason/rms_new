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
                <a href="menu.html" class="button">All Dishes</a>
                <a href="reserve_tb.html" class="button">Reserve a table</a>
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
        <div class="dish">
            <img src="Kung Pao Shrimps.jpg" alt="Kung Pao Shrimps" style="width: 1265px; ">
            <div>
                <h2>Kung Pao Shrimps</h2>
                <p>Points: 15</p>
                <p>Introduction: Kung Pao Shrimps is a classic dish made with shrimps, peanuts, and dried chili peppers. It has a spicy and fragrant taste and is rich in nutrition.</p>
                <p>Price: $38</p>
                <button class="add-to-cart-button">Add to Cart</button>
            </div>
        </div>
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