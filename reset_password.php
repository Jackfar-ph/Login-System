<?php
require 'db.php';

// 1. Check if token is in the URL (GET)
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $hash = hash("sha256", $token);

    // 2. Validate token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token_hash = ?");
    $stmt->execute([$hash]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Token invalid or expired.");
    }

    // 3. Handle Form Submission (POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Update the password and clear the token
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?");
        $stmt->execute([$new_password, $user['id']]);

        // SUCCESS MESSAGE
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Success</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
            <div class='container'>
                <div class='message' style='text-align:center;'>
                    <h2 style='color: #28a745;'>✔ Success!</h2>
                    <p>Your password has been updated successfully.</p>
                    <br>
                    <a href='login.php' style='text-decoration: none; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px;'>Login Now</a>
                </div>
            </div>
        </body>
        </html>";
        exit; 
    }

} else {
    die("Access denied. No reset token provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>New Password</h2>
            <p style="font-size: 14px; color: #666;">Please enter your new secure password below.</p>
            
            <input type="password" name="password" required placeholder="New Password">
            <button type="submit">Update Password</button>
            
            <p><a href="login.php">Back to Login</a></p>
        </form>
    </div>
</body>
</html>