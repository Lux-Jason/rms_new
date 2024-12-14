<?php
session_start();
include 'connectdb.php';  // 包含数据库连接

// 验证密码复杂度
function validatePassword($password) {
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one digit.";
    }
    if (!preg_match('/[\W_]/', $password)) {
        return "Password must contain at least one special character.";
    }
    return true;
}

// 生成和验证 CSRF Token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return $token === $_SESSION['csrf_token'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // 验证 CSRF Token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        echo "<div class='feedback' style='color: red;'>Invalid CSRF Token</div>";
        exit();
    }

    // 获取并清理表单输入
    $s_name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $s_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // 验证密码复杂度
    $passwordValidation = validatePassword($s_password);
    if ($passwordValidation !== true) {
        echo "<div class='feedback' style='color: red;'>$passwordValidation</div>";
        exit();
    }

    // 检查用户名是否已存在
    $stmt = $conn->prepare("SELECT s_name FROM seller WHERE s_name = :s_name");
    $stmt->bindValue(':s_name', $s_name, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<div class='feedback' style='color: red;'>Username already exists!</div>";
    } else {
        // 将密码加密并插入到数据库
        $s_password_hash = password_hash($s_password, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO seller (s_name, s_password) VALUES (:s_name, :s_password)");
        $insert_stmt->bindValue(':s_name', $s_name, PDO::PARAM_STR);
        $insert_stmt->bindValue(':s_password', $s_password_hash, PDO::PARAM_STR);
        $insert_stmt->execute();

        // 注册成功后，跳转到登录页面
        header("Location: admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapSpot Register</title>
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
        .links {
            color: #00aeec;
        }
        .links:hover {
            color: #00aeec;
        }
        .feedback {
            font-size: 14px;
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- Registration Form -->
<div class="main-container">
    <div class="login-container">
        <img src="qiao_logo.svg" style="width: 300px;">
        <h2>Admin Register</h2>
        <form method="post">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="text" name="username" placeholder="Username" id="username" required>
            <div id="usernameFeedback" class="feedback"></div>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <div id="passwordFeedback" class="feedback"></div>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="admin.php" class="links">Login here</a>.</p>
    </div>
</div>

<script>
    // 前端密码复杂度验证
    document.getElementById('password').addEventListener('input', function() {
        var password = this.value;
        var feedback = document.getElementById('passwordFeedback');
        feedback.innerHTML = '';
        if (password.length < 8) {
            feedback.innerHTML = 'Password must be at least 8 characters.';
            return;
        }
        if (!/[A-Z]/.test(password)) {
            feedback.innerHTML = 'Password must contain at least one uppercase letter.';
            return;
        }
        if (!/[0-9]/.test(password)) {
            feedback.innerHTML = 'Password must contain at least one digit.';
            return;
        }
        if (!/[\W_]/.test(password)) {
            feedback.innerHTML = 'Password must contain at least one special character.';
            return;
        }
        feedback.innerHTML = 'Password is strong.';
    });
</script>

</body>
</html>
