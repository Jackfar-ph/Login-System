<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $hash = hash("sha256", $token);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()");
    $stmt->execute([$hash]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Token invalid or expired.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Update password and clear the token so it can't be used again
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?");
        $stmt->execute([$new_password, $user['id']]);

        echo "Password updated! <a href='login.php'>Login here</a>";
    }
}
?>

<form method="post">
    <h2>Enter New Password</h2>
    <input type="password" name="password" required placeholder="New Password">
    <button type="submit">Update Password</button>
</form>