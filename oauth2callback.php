<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'vendor/autoload.php';
require 'config.php';
require 'db_config.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            $googleId = $google_account_info->id ?? '';
            $email = $google_account_info->email ?? '';
            $name = $google_account_info->name ?? $email;
            $pictureUrl = $google_account_info->picture ?? '';
            $profileUrl = $google_account_info->link ?? '';

            if (empty($googleId) || empty($email)) {
                $_SESSION['flash_error'] = 'Unable to verify your Google account information.';
                header('Location: index.php');
                exit();
            }

            $stmt = $conn->prepare('SELECT id FROM users WHERE google_id = ? OR email = ? LIMIT 1');
            $stmt->bind_param('ss', $googleId, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userId = $row['id'];
                $updateStmt = $conn->prepare('UPDATE users SET google_id = ?, email = ?, name = ?, picture_url = ?, profile_url = ?, last_login = NOW() WHERE id = ?');
                $updateStmt->bind_param('sssssi', $googleId, $email, $name, $pictureUrl, $profileUrl, $userId);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                $insertStmt = $conn->prepare('INSERT INTO users (google_id, email, name, picture_url, profile_url, last_login) VALUES (?, ?, ?, ?, ?, NOW())');
                $insertStmt->bind_param('sssss', $googleId, $email, $name, $pictureUrl, $profileUrl);
                $insertStmt->execute();
                $userId = $insertStmt->insert_id;
                $insertStmt->close();
            }

            $stmt->close();

            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['login_method'] = 'google';

            header('Location: welcome.php');
            exit();
        }

        $_SESSION['flash_error'] = 'Google authentication failed. Please try again.';
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['flash_error'] = 'Google sign-in error: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }
}

header('Location: index.php');
exit();
