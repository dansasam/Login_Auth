<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'vendor/autoload.php';
require 'config.php';

if (isset($_SESSION['email'])) {
    header("Location: welcome.php");
    exit();
}

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();
$flashError = $_SESSION['flash_error'] ?? '';
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Authentication</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 30px;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            padding: 40px 36px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 12px;
        }

        .card p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 28px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            font-size: 13px;
            color: #555;
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #dcdcdc;
            font-size: 15px;
            transition: border-color 0.25s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .submit-button,
        .login-button {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .submit-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 10px;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 35px rgba(102, 126, 234, 0.25);
        }

        .login-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: white;
            border: 1px solid #e0e0e0;
            color: #333;
            text-decoration: none;
            margin-top: 10px;
        }

        .login-button:hover {
            background: #f8f8f8;
            border-color: #667eea;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.18);
            transform: translateY(-2px);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 22px;
            font-size: 14px;
            text-align: left;
        }

        .alert-error {
            background: #ffe3e3;
            color: #a32f2f;
        }

        .alert-success {
            background: #e8f7e9;
            color: #1f7a3a;
        }

        .register-link {
            margin-top: 16px;
            display: block;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 30px 0 20px;
        }

        .divider span {
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .divider strong {
            color: #7c7c7c;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 860px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Sign in to your account</h1>
            <p>Use your email and password, or choose Google sign-in for the fastest access.</p>

            <?php if ($flashError): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($flashError); ?></div>
            <?php endif; ?>

            <?php if ($flashSuccess): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($flashSuccess); ?></div>
            <?php endif; ?>

            <form action="login_process.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="submit-button">Sign in</button>
            </form>

            <div class="divider"><span></span><strong>or</strong><span></span></div>

            <a href="<?php echo $login_url; ?>" class="login-button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign in with Google
            </a>

            <a href="register.php" class="register-link">Create a new account</a>
        </div>

        <div class="card">
            <h1>Secure access made easy</h1>
            <p>Register once with a secure password, then sign in locally or use Google account sign-in anytime. Your credentials are protected with modern hashing and secure sessions.</p>
            <div class="features">
                <p><i class="fas fa-check" style="color: #34A853;"></i> Local email/password registration</p>
                <p><i class="fas fa-check" style="color: #34A853;"></i> Seamless Google OAuth login</p>
                <p><i class="fas fa-check" style="color: #34A853;"></i> Clean, secure design</p>
            </div>
        </div>
    </div>
</body>
</html>
