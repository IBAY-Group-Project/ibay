
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginMode = $_POST['login_mode'] ?? 'email';
    $login = trim($_POST['email'] ?? $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($loginMode === 'email') {
        $sql = "SELECT * FROM iBayMembers WHERE email='$login'";
    } else {
        $sql = "SELECT * FROM iBayMembers WHERE username='$login'";
    }

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("SQL ERROR: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['userId'] = $row['userId'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['is_admin'] = $row['is_admin'];

            header("Location: /ibay/index.php");
            exit();
        } else {
            echo "PASSWORD WRONG";
        }
    } else {
        echo "USER NOT FOUND";
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
    </header>

    <main class="auth-page">
        <section class="auth-card">
            <h1>Login to iBay</h1>
            <p class="auth-subtitle">Access your account to manage listings, saved items, and purchases.</p>

   
            <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=420551119650-1vo8vduihfvnq3jrs0ii3etbl60kamr8.apps.googleusercontent.com&redirect_uri=http://localhost/ibay/google_callback.php&response_type=code&scope=email%20profile" class="google-button">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="G" width="18" height="18">
                Sign in with Google
            </a>

            <div class="auth-divider"><span>or</span></div>

            <form class="auth-form" action="login.php" method="post">
                <div class="form-group">
                    <div class="login-toggle-row">
                        <label for="email">Email</label>
                        <button type="button" id="toggleLoginType" class="toggle-link">Use username instead</button>
                    </div>
                    <input type="text" name="email" id="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group" id="usernameGroup" style="display: none;">
                    <div class="login-toggle-row">
                        <label for="username">Username</label>
                        <button type="button" id="toggleLoginType2" class="toggle-link">Use email instead</button>
                    </div>
                    <input type="text" name="username" id="username" placeholder="Enter your username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <input type="hidden" name="login_mode" id="login_mode" value="email">

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