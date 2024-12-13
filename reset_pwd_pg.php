<!DOCTYPE html>
<!-- Full website is under construction. -->

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
            background-repeat: no-repeat; /* repeat pic */
            height: 100vh; /* fill the page */
        }
        .main-container {
            display: flex;
            position: relative;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset_pwd-container {
            width: 30%;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .reset_pwd-container h2 {
            margin-top: 0;
            text-align: center;
        }
        .reset_pwd-container form {
            text-align: center;
        }
        .reset_pwd-container input[type="text"],
        .reset_pwd-container input[type="password"],
        .reset_pwd-container button,
        .reset_pwd-container select {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .reset_pwd-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
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
        <div class="reset_pwd-container">
            <img src="qiao_logo.svg" style="width: 300px;">
            <h1 style="font-family: 'Segoe UI Light', Arial, sans-serif; padding: 0px; margin: 0px; color: #aaa;" id="greetings"></h1>
            <h2 style="margin: 20px;">Reset Password</h2>
            <form action="reset_pwd_action.php" method="post">
                <input type="text" name="username" placeholder="Username" id="username" onfocus="inputFocus(this)" required>
                <select name="security_question" placeholder="Select Your Security Question" id="security_question" onfocus="inputFocus(this)" required> 
                    <option value="" disabled selected>Select Security Question</option>
                    <option value="city">Your birth city?</option>
                    <option value="primary_school_name">What was your primary school name?</option>
                    <option value="favorite_pet">Your favorite pet?</option>
                    <option value="first_phone">Your first phone brand and model?</option>
                    <option value="favorite_song">Your favourite song</option>
                </select>
                <input type="text" name="answer" placeholder="Answer" onfocus="inputFocus(this)" required>
                <input type="password" name="password" placeholder="Password" id="password" onfocus="inputFocus(this)" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" id="conf_password" onfocus="inputFocus(this)" required>

                <button type="submit">Reset Password</button>
            </form>
            <p><a href="login_page.php" class="links">Back to Login Page</a></p>
        </div>
    </div>
    
    <script>
        var currentDate = new Date();
        var currentHour = currentDate.getHours();
        var body = document.body;
        if (currentHour<4 || currentHour>19) {
            body.style.backgroundImage = "url('bg_19_04.jpg')";
            document.getElementById("greetings").innerHTML = "May you a good night.";
        } else if (currentHour>=4 || currentHour<7) {
            body.style.backgroundImage = "url('bg_04_07.jpg')";
            document.getElementById("greetings").innerHTML = "Good morning. <br>A new day is about to begin.";
        } else if (currentHour>16 || currentHour<=19) {
            body.style.backgroundImage = "url('bg_16_19.jpg')";
            document.getElementById("greetings").innerHTML = "The day's coming to an end. <br>Now cherish the evening sunset.";
        } else {
            body.style.backgroundImage = "url('bg_07_16.jpg')";
            document.getElementById("greetings").innerHTML = "May you have a fulfilling day.";
        }
        function inputFocus(input) {
            input.style.backgroundColor = "white";
            input.style.borderColor = "#00AEEC";
        }
    </script>
    
    </body>
</html>