<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'vendor/autoload.php';

if (isset($_SESSION['email'])) {
    header("Location: welcome.php");
    exit();
}

$client = new Google_Client();
$client->setClientId('386265100104-7ojpvi7jl6crtrpjt10rlr92tk7tlgr5.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/login_auth/oauth2callback.php');
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Google - Authentication</title>
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
            overflow: hidden;
        }

        .container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px 40px;
            text-align: center;
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

        .logo-section {
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 60px;
            color: #667eea;
            margin-bottom: 15px;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .login-button {
            display: inline-block;
            background: white;
            border: 2px solid #e0e0e0;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 10px;
            cursor: pointer;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
        }

        .login-button:hover {
            background: #f8f8f8;
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .login-button img {
            height: 20px;
            width: auto;
        }

        .features {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
        }

        .features p {
            color: #999;
            font-size: 12px;
            margin-top: 10px;
            line-height: 1.6;
        }

        .security-badge {
            font-size: 12px;
            color: #667eea;
            margin-top: 20px;
        }

        .security-badge i {
            margin-right: 5px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 40px 30px;
            }

            h1 {
                font-size: 24px;
            }

            .logo-icon {
                font-size: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">🔐</div>
                <h1>Welcome Back</h1>
                <p class="subtitle">Sign in securely with your Google account to access your dashboard</p>
            </div>

            <a href="<?php echo $login_url; ?>" class="login-button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign in with Google
            </a>

            <div class="features">
                <p><i class="fas fa-check" style="color: #34A853;"></i> Secure OAuth 2.0 authentication</p>
                <p><i class="fas fa-check" style="color: #34A853;"></i> Your data is protected</p>
                <p><i class="fas fa-check" style="color: #34A853;"></i> Easy account access</p>
            </div>

            <div class="security-badge">
                <i class="fas fa-shield-alt"></i> Your privacy is our priority
            </div>
        </div>
    </div>
</body>
</html>