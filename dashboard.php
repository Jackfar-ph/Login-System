<?php
session_start();

// Security Check: If the session doesn't exist, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome Back!</h2>
        <div class="message" style="background: #d4edda; color: #155724;">
            You have successfully logged into your secure account.
        </div>
        <p>This is your private dashboard area.</p>
        <br>
        <a href="logout.php" style="background: #dc3545; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block;">Logout</a>
    </div>
</body>
</html>