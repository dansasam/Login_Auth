<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('386265100104-7ojpvi7jl6crtrpjt10rlr92tk7tlgr5.apps.googleusercontent.com');
$client->setClientSecret('yousecret');
$client->setRedirectUri('http://localhost/login_auth/oauth2callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            
            // Set session variables
            $_SESSION['email'] = $google_account_info->email;
            $_SESSION['name'] = $google_account_info->name;
            
            // Save session before redirect
            session_write_close();
            
            echo "Login successful! Redirecting...";
            header('Location: welcome.php');
            exit();
        } else {
            echo "Error: " . $token['error'] . "<br>";
            echo "Error Description: " . (isset($token['error_description']) ? $token['error_description'] : 'No description') . "<br>";
            echo "Redirecting to login in 3 seconds...";
            header('refresh:3;url=index.php');
        }
    } catch (Exception $e) {
        echo "Exception Error: " . $e->getMessage() . "<br>";
        echo "Trace: " . $e->getTraceAsString() . "<br>";
        echo "Redirecting to login in 3 seconds...";
        header('refresh:3;url=index.php');
    }
} else {
    echo "No authorization code received.";
    header('Location: index.php');
    exit();
}