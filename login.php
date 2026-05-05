<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("connection.php");

if (isset($_POST['login'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE email='$email' OR username='$email'");
    

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // Store user info in session
            $_SESSION['userId']   = $row['userId'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['email']     = $row['email'];

            // Redirect to homepage
            header("Location: /ibay/index.php");
            exit();
        } else {
            echo "<script> alert('Incorrect password!'); window.history.back(); </script>";
        }
    } else {
        echo "<script> alert('Email not found!'); window.history.back(); </script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Home</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

    <header class="site-header">

        <?php include("includes/navbar.php"); ?>

    <main class="auth-page">
        <section class="auth-card">
            <h1>Login to iBay</h1>
            <p class="auth-subtitle">Access your account to manage listings, saved items, and purchases.</p>

            <form class="auth-form" action="login.php" method="post">
                
                <div class="form-group">
                    <div class="login-toggle-row">
                        <label for="email">Email</label>
                        <button type="button" id="toggleLoginType" class="toggle-link">Use username instead</button>
                    </div>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group" id="usernameGroup" style="display: none;">
                    <div class="login-toggle-row">
                        <label for="username">Username</label>
                        <button type="button" id="toggleLoginType2" class="toggle-link">Use email instead</button>
                    </div>
                    <input type="text" id="username" name="email" placeholder="Enter your username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="login-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>

                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" name="login" class="primary-button">Login</button>

                <p class="auth-switch">
                    New to iBay?
                    <a href="signup.html">Create an account</a>
                </p>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>