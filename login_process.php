<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['flash_error'] = 'Please enter both email and password.';
    header('Location: index.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = 'Please enter a valid email address.';
    header('Location: index.php');
    exit();
}

$stmt = $conn->prepare('SELECT id, name, password_hash FROM local_users WHERE email = ? AND is_active = 1 LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['flash_error'] = 'No account was found with that email. Please register first.';
    header('Location: index.php');
    exit();
}

$stmt->bind_result($userId, $name, $passwordHash);
$stmt->fetch();
$stmt->close();

if (!password_verify($password, $passwordHash)) {
    $_SESSION['flash_error'] = 'The password you entered is incorrect. Please try again.';
    header('Location: index.php');
    exit();
}

$_SESSION['user_id'] = $userId;
$_SESSION['email'] = $email;
$_SESSION['name'] = $name;
$_SESSION['login_method'] = 'local';

header('Location: welcome.php');
exit();
