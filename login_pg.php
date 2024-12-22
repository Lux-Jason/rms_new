<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qiao's Handmade Login</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('bg_07_16.jpg');
            background-size: 100%;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
        .main-container {
            display: flex;
            position: relative;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 30%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .login-container h2 {
            margin-top: 0;
            text-align: center;
        }
        .login-container form {
            text-align: center;
        }
        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .login-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .login-container button:focus {
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 0, 255, 0.5);
        }
        .links {
            color: #00aeec;
        }
        .links:hover {
            color: #00aeec;
        }
        .links:visited {
            color: #00aeec;
        }
        .warning {
            color: red;
            font-size: 0.9em;
            display: none;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="login-container">
        <img src="qiao_logo.svg" style="width: 300px;">
        <h1 style="font-family: 'Segoe UI Light', Arial, sans-serif; padding: 0px; margin: 0px; color: #aaa;" id="greetings"></h1>
        <h2 style="margin: 20px;">Login</h2>
        <form id="loginForm" action="logincheck_users.php" method="post">
            <input type="text" name="username" placeholder="Username" id="username" onfocus="inputFocus(this)" required>
            <input type="password" name="password" placeholder="Password" id="password" onfocus="inputFocus(this)" required>
            <div id="warning" class="warning">Invalid username or password!</div>
            <button type="submit">Login</button>
        </form>
        <p><a href="reset_pwd_pg.php" class="links">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php" class="links">Register</a></p>
    </div>
</div>

<script>
    var currentDate = new Date();
    var currentHour = currentDate.getHours();
    var body = document.body;
    if (currentHour < 4 || currentHour > 19) {
        body.style.backgroundImage = "url('bg_19_04.jpg')";
        document.getElementById("greetings").innerHTML = "May you a good night.";
    } else if (currentHour >= 4 && currentHour < 7) {
        body.style.backgroundImage = "url('bg_04_07.jpg')";
        document.getElementById("greetings").innerHTML = "Good morning. <br>A new day is about to begin.";
    } else if (currentHour >= 7 && currentHour < 16) {
        body.style.backgroundImage = "url('bg_07_16.jpg')";
        document.getElementById("greetings").innerHTML = "May you have a fulfilling day.";
    } else {
        body.style.backgroundImage = "url('bg_16_19.jpg')";
        document.getElementById("greetings").innerHTML = "The day's coming to an end. <br>Now cherish the evening sunset.";
    }

    function inputFocus(input) {
        input.style.backgroundColor = "white";
        input.style.borderColor = "#00AEEC";
    }

    // Form validation
    document.getElementById('loginForm').onsubmit = function(event) {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        var warning = document.getElementById('warning');

        if (username === "" || password === "") {
            warning.innerText = "Username and password cannot be empty!";
            warning.style.display = 'block';  // 显示警告信息
            event.preventDefault(); // 阻止表单提交
        } else {
            warning.style.display = 'none';  // 隐藏警告信息
        }
    };
</script>

</body>
</html>
