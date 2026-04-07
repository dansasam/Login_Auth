<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
    $_SESSION['flash_error'] = 'Please fill out all registration fields.';
    header('Location: register.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = 'Please enter a valid email address.';
    header('Location: register.php');
    exit();
}

if ($password !== $confirmPassword) {
    $_SESSION['flash_error'] = 'Passwords do not match.';
    header('Location: register.php');
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['flash_error'] = 'Password must be at least 8 characters long.';
    header('Location: register.php');
    exit();
}

$stmt = $conn->prepare('SELECT id FROM local_users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['flash_error'] = 'An account already exists with that email address.';
    $stmt->close();
    header('Location: register.php');
    exit();
}
$stmt->close();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO local_users (name, email, password_hash) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $passwordHash);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['login_method'] = 'local';
    $stmt->close();
    header('Location: welcome.php');
    exit();
}

$_SESSION['flash_error'] = 'Unable to complete registration. Please try again.';
$stmt->close();
header('Location: register.php');
exit();
