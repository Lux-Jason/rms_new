<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .register-container {
            width: 30%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .register-container h2 {
            margin-top: 0;
            text-align: center;
        }
        .register-container form {
            text-align: center;
        }
        .register-container input[type="text"],
        .register-container input[type="password"],
        .register-container button,
        .register-container select {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .register-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .warning {
            color: red;
            font-size: 0.9em;
            display: none;
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
    </style>
</head>
<body>
<div class="main-container">
    <div class="register-container">
        <img src="qiao_logo.svg" style="width: 300px;">
        <h1 style="font-family: 'Segoe UI Light', Arial, sans-serif; padding: 0px; margin: 0px; color: #aaa;" id="greetings"></h1>
        <h2 style="margin: 20px;">Registry</h2>
        <form id="registerForm" method="post">
            <input type="text" name="username" placeholder="Username" id="username" onfocus="inputFocus(this)" required>
            <input type="password" name="password" placeholder="Password" id="password" onfocus="inputFocus(this)" required>
            <input type="password" name="conf_password" placeholder="Confirm Password" id="conf_password" onfocus="inputFocus(this)" required>
            <div class="warning" id="passwordWarning">Passwords do not match!</div>
            <select name="security_question" id="security_question" style="background-color: #ccc;" onfocus="inputFocus(this)" required>
                <option value="" disabled selected>Select Security Question 1</option>
                <option value="first_pet">What is the name of your first pet?</option>
                <option value="birth_city">What is the name of the city where you were born?</option>
                <option value="best_childhood_friend">What is the name of your best childhood friend?</option>
                <option value="primary_school_name">What's your primary school's name?</option>
                <option value="street_grew_up_on">What is the name of the street you grew up on?</option>
            </select>
            <input type="text" name="answer" id="answer" placeholder="Answer" onfocus="inputFocus(this)" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login_page.php" class="links">Login</a></p>
    </div>
</div>

<script>
    var currentDate = new Date();
    var currentHour = currentDate.getHours();
    var body = document.body;
    if (currentHour < 4 || currentHour > 19) {
        body.style.backgroundImage = "url('bg_19_04.jpg')";
        document.getElementById("greetings").innerHTML = "May you have a good night.";
    } else if (currentHour >= 4 && currentHour < 7) {
        body.style.backgroundImage = "url('bg_04_07.jpg')";
        document.getElementById("greetings").innerHTML = "Good morning. <br>A new day is about to begin.";
    } else if (currentHour >= 7 && currentHour < 16) {
        body.style.backgroundImage = "url('bg_07_16.jpg')";
        document.getElementById("greetings").innerHTML = "May you have a fulfilling day.";
    } else if (currentHour >= 16 && currentHour <= 19) {
        body.style.backgroundImage = "url('bg_16_19.jpg')";
        document.getElementById("greetings").innerHTML = "The day's coming to an end. <br>Now cherish the evening sunset.";
    }

    function inputFocus(input) {
        input.style.backgroundColor = "white";
        input.style.borderColor = "#00AEEC";
    }

    document.getElementById('registerForm').onsubmit = function(event) {
        var password = document.getElementById('password').value;
        var confPassword = document.getElementById('conf_password').value;
        var warning = document.getElementById('passwordWarning');

        if (password !== confPassword) {
            warning.style.display = 'block';
            event.preventDefault(); // Prevent form submission
        } else {
            warning.style.display = 'none';
        }
    };
</script>

<?php
include 'connectdb.php'; // 包含数据库连接

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取 POST 请求中的字段并进行转义处理
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['conf_password']) ? $_POST['conf_password'] : '';
    $safety_question = isset($_POST['security_question']) ? $conn->real_escape_string($_POST['security_question']) : '';
    $answer = isset($_POST['answer']) ? $conn->real_escape_string($_POST['answer']) : '';

    // 检查密码是否匹配
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // 使用 password_hash() 函数加密密码
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 插入数据的 SQL 语句
        $sql = "INSERT INTO buyer (buyer_name, b_password, safety_question, answer) 
                VALUES (?, ?, ?, ?)";

        // 准备 SQL 语句
        $stmt = $conn->prepare($sql);

        // 绑定参数
        $stmt->bind_param("ssss", $username, $hashed_password, $safety_question, $answer);

        // 执行 SQL 语句
        if ($stmt->execute()) {
            // 注册成功，弹出提示框并跳转到登录页面
            echo "<script>alert('Registration successful!'); window.location.href = 'login_page.php';</script>";
        } else {
            // 如果执行失败，弹出错误信息
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // 关闭 SQL 语句
        $stmt->close();
    }
    // 关闭数据库连接
    $conn->close();
}
?>


</body>
</html>
