<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $_POST['username'];
            $email = $_POST['email'];
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$user, $email, $pass]);
                echo "<div class='message' style='color:green;'>Success! <a href='login.php'>Login here</a></div>";
            } catch (PDOException $e) {
                echo "<div class='message' style='color:red;'>Username or Email already exists.</div>";
            }
        }
        ?>

        <form method="post">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit">Create Account</button>
        </form>
        <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>
</body>
</html>