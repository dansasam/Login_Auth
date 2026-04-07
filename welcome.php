<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

$loginMethod = $_SESSION['login_method'] ?? 'Google OAuth 2.0';
$displayName = $_SESSION['name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Dashboard</title>
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
            padding: 20px;
        }

        .navbar {
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 600;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 28px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-card {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .welcome-card h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .welcome-card p {
            font-size: 16px;
            opacity: 0.9;
        }

        .card-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: #667eea;
            font-size: 24px;
        }

        .info-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #999;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .info-value {
            color: #333;
            font-size: 16px;
            word-break: break-all;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            background: #34A853;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .action-button {
            background: #f0f0f0;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
            color: #333;
        }

        .action-button:hover {
            background: #667eea;
            color: white;
        }

        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 30px;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 20px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .welcome-card h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-shield-alt"></i>
            Login Auth System
        </div>
        <div class="navbar-right">
            <span style="color: #666; font-size: 14px;">
                <i class="fas fa-user-circle"></i> 
                <?php echo htmlspecialchars($displayName); ?>
            </span>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-grid">
            <div class="card welcome-card">
                <h1>Welcome, <?php echo htmlspecialchars(explode(' ', $displayName)[0]); ?>!</h1>
                <p>You are successfully logged in to your account.</p>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-user"></i> Account Information
                </div>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($displayName); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Status</div>
                    <div class="status-badge"><i class="fas fa-check-circle"></i> Active</div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-lock"></i> Security
                </div>
                <div class="info-item">
                    <div class="info-label">Login Method</div>
                    <div class="info-value"><?php echo htmlspecialchars($loginMethod === 'local' ? 'Email and password' : 'Google OAuth 2.0'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Session Status</div>
                    <div class="status-badge"><i class="fas fa-check-circle"></i> Secure</div>
                </div>
                <button class="action-button">
                    <i class="fas fa-lock"></i> Update Security Settings
                </button>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-cog"></i> Quick Actions
                </div>
                <div class="info-item">
                    <p style="color: #666; font-size: 14px; line-height: 1.6;">
                        Manage your account settings, security preferences, and privacy options from here.
                    </p>
                </div>
                <button class="action-button">
                    <i class="fas fa-user-cog"></i> Account Settings
                </button>
                <button class="action-button">
                    <i class="fas fa-key"></i> Connected Apps
                </button>
            </div>
        </div>

        <div class="footer">
            <p><i class="fas fa-shield-alt"></i> Your data is secure and protected with industry-standard encryption</p>
        </div>
    </div>
</body>
</html>


    <div class="container">
        <div class="dashboard-grid">
            <div class="card welcome-card">
                <h1>Welcome, <?php echo htmlspecialchars(explode(' ', $_SESSION['name'])[0]); ?>!</h1>
                <p>You are successfully logged in to your account</p>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-user"></i> Account Information
                </div>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Status</div>
                    <div class="status-badge"><i class="fas fa-check-circle"></i> Active</div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-lock"></i> Security
                </div>
                <div class="info-item">
                    <div class="info-label">Login Method</div>
                    <div class="info-value">Google OAuth 2.0</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Session Status</div>
                    <div class="status-badge"><i class="fas fa-check-circle"></i> Secure</div>
                </div>
                <button class="action-button">
                    <i class="fas fa-lock"></i> Update Security Settings
                </button>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fas fa-cog"></i> Quick Actions
                </div>
                <div class="info-item">
                    <p style="color: #666; font-size: 14px; line-height: 1.6;">
                        Manage your account settings, security preferences, and privacy options from here.
                    </p>
                </div>
                <button class="action-button">
                    <i class="fas fa-user-cog"></i> Account Settings
                </button>
                <button class="action-button">
                    <i class="fas fa-key"></i> Connected Apps
                </button>
            </div>
        </div>

        <div class="footer">
            <p><i class="fas fa-shield-alt"></i> Your data is secure and protected with industry-standard encryption</p>
        </div>
    </div>
</body>
</html>