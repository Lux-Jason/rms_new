<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Information</title>

    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">

    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .info-form {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .info-form input {
            display: block;
            width: 300px;
            margin-bottom: 15px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .info-form button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
                <a href="reserve_tb.html" class="button">Reserve a table</a>
            </nav>
        </div>
        <div>
            <p>Welcome! Mr. John Doe. </p>

        </div>
    </header>

    <div id="reserved_area" style="height: 32px;"></div>
    
    <div class="container">
        <h1>Personal Information</h1>
        <form class="info-form">
            <label for="name">Name:</label>
            <input type="text" id="name" value="John Doe">
            <label for="email">Email:</label>
            <input type="email" id="email" value="johndoe@example.com">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" value="12345678901">
            <button type="submit">Save Changes</button>
        </form>
    </div>

        
    <div id="reserved_area" style="height: 128px;"></div>
    
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