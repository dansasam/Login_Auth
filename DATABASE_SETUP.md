# Database Setup Instructions for Login Auth System

## Overview
This database system is designed for a Google OAuth2 login application with user tracking and session management.

## Database Tables

### 1. **users** - Stores Google OAuth user information
- `id` - Primary key
- `google_id` - Google's unique user ID
- `email` - User email
- `name` - User full name
- `picture_url` - User's profile picture URL
- `profile_url` - User's Google profile URL
- `created_at` - Account creation timestamp
- `updated_at` - Last profile update timestamp
- `last_login` - Last login timestamp
- `is_active` - Account status

### 2. **login_history** - Tracks user login/logout activity
- `id` - Primary key
- `user_id` - Foreign key to users table
- `login_time` - When user logged in
- `logout_time` - When user logged out
- `ip_address` - User's IP address
- `user_agent` - Browser/device information
- `session_id` - Session identifier

### 3. **sessions** - Manages OAuth tokens and session data
- `id` - Primary key
- `user_id` - Foreign key to users table
- `session_token` - Unique session identifier
- `access_token` - Google OAuth access token
- `refresh_token` - Google OAuth refresh token
- `token_expires_at` - Token expiration time
- `created_at` - Session creation time
- `expires_at` - Session expiration time

### 4. **user_preferences** - Stores user settings
- `id` - Primary key
- `user_id` - Foreign key to users table
- `theme` - UI theme preference (light/dark)
- `language` - Preferred language
- `notifications_enabled` - Notification settings

## Setup Instructions

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Start Apache and MySQL services

### Step 2: Import Database

#### Option A: Using phpMyAdmin (Easiest)
1. Open browser and go to `http://localhost/phpmyadmin`
2. Click on "Import" tab at the top
3. Click "Choose File" and select `database.sql`
4. Click "Go" to import

#### Option B: Using MySQL Command Line
1. Open Command Prompt/PowerShell
2. Navigate to your XAMPP installation directory:
   ```
   cd C:\xampp\mysql\bin
   ```
3. Run the following command (in your project folder):
   ```
   mysql -u root -p login_auth < C:\xampp\htdocs\login_auth\database.sql
   ```
4. Press Enter when prompted for password (if no password, just press Enter)

#### Option C: Using MySQL Workbench
1. Open MySQL Workbench
2. Connect to your local MySQL server
3. Go to File > Open SQL Script
4. Select `database.sql`
5. Click Execute (lightning bolt icon)

### Step 3: Verify Installation
1. Go to `http://localhost/phpmyadmin`
2. In the left sidebar, you should see the `login_auth` database
3. Click on it to view the 4 tables: `users`, `login_history`, `sessions`, `user_preferences`

### Step 4: Update Your PHP Files (Optional)
If you want to use the database, include the connection file in your PHP files:
```php
<?php
require 'db_config.php';
// Now you can use $conn to query the database
?>
```

## Example: Insert User After Google OAuth Login
After a successful Google OAuth login, you can store the user in the database:

```php
// In oauth2callback.php after successful authentication
require 'db_config.php';

$google_id = $google_account_info->id; // If available from Google API
$email = $google_account_info->email;
$name = $google_account_info->name;
$picture_url = $google_account_info->picture ?? null;

// Check if user exists
$check_query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update last_login
    $update_query = "UPDATE users SET last_login = NOW() WHERE email = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
} else {
    // Insert new user
    $insert_query = "INSERT INTO users (google_id, email, name, picture_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $google_id, $email, $name, $picture_url);
    $stmt->execute();
}

$stmt->close();
```

## Default Credentials
- **Host:** localhost
- **Username:** root
- **Password:** (empty by default in XAMPP)
- **Database:** login_auth

## Notes
- Make sure MySQL is running before trying to import
- The database uses InnoDB storage engine for foreign key support
- All timestamps use UTC timezone
- User pictures and profile URLs are optional fields
