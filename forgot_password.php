<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <p style="font-size: 14px; color: #666;">Enter your email to receive a reset link.</p>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $token = bin2hex(random_bytes(32));
            $hash = hash("sha256", $token);
            $expiry = date("Y-m-d H:i:s", time() + 1800);

            $stmt = $pdo->prepare("UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
            $stmt->execute([$hash, $expiry, $email]);

            // Only show the simulated link when a row was updated (email exists).
            if ($stmt->rowCount() > 0) {
                echo "<div class='message' style='color:green;'>Link sent! <br><a href='reset_password.php?token=$token'>Simulate Email Link</a></div>";
            } else {
                // To avoid user enumeration, show the same message but don't reveal a link.
                echo "<div class='message' style='color:green;'>If that email exists, a reset link has been sent.</div>";
                error_log("forgot_password: attempted reset for non-existent email: $email");
            }
        }
        ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>