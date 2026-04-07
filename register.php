<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['email'])) {
    header('Location: welcome.php');
    exit();
}

$flashError = $_SESSION['flash_error'] ?? '';
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Login Auth</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .form-card { width: 100%; max-width: 420px; background: #fff; border-radius: 20px; padding: 40px 35px; box-shadow: 0 25px 70px rgba(0,0,0,0.15); }
        h1 { font-size: 28px; margin-bottom: 10px; color: #333; }
        p.subtitle { color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.6; }
        .input-group { margin-bottom: 18px; }
        .input-group label { display: block; color: #555; font-size: 13px; margin-bottom: 6px; }
        .input-group input { width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid #dcdcdc; font-size: 15px; transition: border-color .25s ease; }
        .input-group input:focus { outline: none; border-color: #667eea; }
        .submit-button { width: 100%; padding: 14px 16px; border: none; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease; }
        .submit-button:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(102, 126, 234, 0.3); }
        .alternate { margin-top: 18px; font-size: 14px; color: #555; text-align: center; }
        .alternate a { color: #667eea; text-decoration: none; font-weight: 600; }
        .alert { padding: 14px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 14px; }
        .alert-error { background: #ffe3e3; color: #b32d2d; }
        .alert-success { background: #e8f7e9; color: #1f7a3a; }
    </style>
</head>
<body>
    <div class="form-card">
        <h1>Create your account</h1>
        <p class="subtitle">Register with your email and password, or go back to login if you already have an account.</p>

        <?php if ($flashError): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($flashError); ?></div>
        <?php endif; ?>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($flashSuccess); ?></div>
        <?php endif; ?>

        <form action="register_process.php" method="POST" novalidate>
            <div class="input-group">
                <label for="name">Full name</label>
                <input type="text" name="name" id="name" placeholder="Jane Doe" required>
            </div>
            <div class="input-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" placeholder="you@example.com" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Create a secure password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat your password" required>
            </div>
            <button type="submit" class="submit-button">Register account</button>
        </form>

        <div class="alternate">
            Already have an account? <a href="index.php">Sign in</a>
        </div>
    </div>
</body>
</html>
