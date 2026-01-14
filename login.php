<?php
require 'db.php';
session_start();

$error_message = ""; // Variable to store errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id();
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit(); 
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container">
        <form method="post">
            <h2>Login</h2>

            <?php if ($error_message): ?>
                <div style="color: red; margin-bottom: 10px; font-size: 14px;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit">Login</button>
            
            <p><a href="forgot_password.php">Forgot Password?</a></p>
            <p>Don't have an account? <a href="register.php">Create New Account</a></p>
        </form>
    </div>
</body>
</html>