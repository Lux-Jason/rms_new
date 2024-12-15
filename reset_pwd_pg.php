<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        .reset-pwd-container {
            width: 30%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .reset-pwd-container h2 {
            margin-top: 0;
            text-align: center;
        }
        .reset-pwd-container form {
            text-align: center;
        }
        .reset-pwd-container input[type="text"],
        .reset-pwd-container input[type="password"],
        .reset-pwd-container button,
        .reset-pwd-container select {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .reset-pwd-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .warning {
            color: red;
            font-size: 0.9em;
            display: none;  /* 默认不显示 */
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
    <div class="reset-pwd-container">
        <img src="qiao_logo.svg" style="width: 300px;">
        <h1 style="font-family: 'Segoe UI Light', Arial, sans-serif; padding: 0px; margin: 0px; color: #aaa;" id="greetings"></h1>
        <h2 style="margin: 20px;">Reset Password</h2>
        <form action="reset_pwd_action.php" id="resetForm" method="post">
            <input type="text" name="username" placeholder="Username" id="username" onfocus="inputFocus(this)" required>
            <input type="password" name="password" placeholder="New Password" id="password" onfocus="inputFocus(this)" required>
            <input type="password" name="conf_password" placeholder="Confirm New Password" id="conf_password" onfocus="inputFocus(this)" required>
            <div id="passwordWarning" class="warning">Passwords do not match!</div>

            <!-- Security Questions -->
            <select name="security_question1" id="security_question1" style="background-color: #ccc;" onfocus="inputFocus(this)" required>
                <option value="" disabled selected>Select Security Question 1</option>
                <option value="first_pet">What is the name of your first pet?</option>
                <option value="birth_city">What is the name of the city where you were born?</option>
                <option value="best_childhood_friend">What is the name of your best childhood friend?</option>
                <option value="primary_school_name">What's your primary school's name?</option>
                <option value="street_grew_up_on">What is the name of the street you grew up on?</option>
            </select>
            <input type="text" name="answer1" placeholder="Answer 1" id="answer1" onfocus="inputFocus(this)" required>

            <select name="security_question2" id="security_question2" style="background-color: #ccc;" onfocus="inputFocus(this)">
                <option value="" disabled selected>Select Security Question 2 (Optional)</option>
                <option value="first_pet">What is the name of your first pet?</option>
                <option value="birth_city">What is the name of the city where you were born?</option>
                <option value="best_childhood_friend">What is the name of your best childhood friend?</option>
                <option value="primary_school_name">What's your primary school's name?</option>
                <option value="street_grew_up_on">What is the name of the street you grew up on?</option>
            </select>
            <input type="text" name="answer2" placeholder="Answer 2 (Optional)" id="answer2" onfocus="inputFocus(this)">

            <select name="security_question3" id="security_question3" style="background-color: #ccc;" onfocus="inputFocus(this)">
                <option value="" disabled selected>Select Security Question 3 (Optional)</option>
                <option value="first_pet">What is the name of your first pet?</option>
                <option value="birth_city">What is the name of the city where you were born?</option>
                <option value="best_childhood_friend">What is the name of your best childhood friend?</option>
                <option value="primary_school_name">What's your primary school's name?</option>
                <option value="street_grew_up_on">What is the name of the street you grew up on?</option>
            </select>
            <input type="text" name="answer3" placeholder="Answer 3 (Optional)" id="answer3" onfocus="inputFocus(this)">

            <button type="submit">Reset Password</button>
        </form>
        <p><a href="login_pg.php" class="links">Back to Login Page</a></p>
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

    document.getElementById('resetForm').onsubmit = function(event) {
        var password = document.getElementById('password').value;
        var confPassword = document.getElementById('conf_password').value;
        var warning = document.getElementById('passwordWarning');

        if (password !== confPassword) {
            warning.style.display = 'block';  // 显示警告信息
            event.preventDefault(); // 阻止表单提交
        } else {
            warning.style.display = 'none';  // 隐藏警告信息
        }
    };
</script>
</body>
</html>
