<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

if (isset($_POST['signup'])) {
	$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    	$surname   = mysqli_real_escape_string($conn, $_POST['surname']);
    	$email     = mysqli_real_escape_string($conn, $_POST['email']);
    	$password  = $_POST['password'];
    	$confirm   = $_POST['confirmPassword'];

    	// 1. Check passwords match
    	if ($password !== $confirm) {
        	die("Passwords do not match");
    	}
 	
	// 2. Password strength checks
	if (strlen($password) < 8) {
    		die("Password must be at least 8 characters long");
	}

	if (!preg_match('/[A-Z]/', $password)) {
    		die("Password must contain at least one uppercase letter");
	}

	if (!preg_match('/[a-z]/', $password)) {
    		die("Password must contain at least one lowercase letter");
	}

	if (!preg_match('/[0-9]/', $password)) {
    		die("Password must contain at least one number");
	}

	if (!preg_match('/[\W]/', $password)) {
    		die("Password must contain at least one special character");
	}
	
   	// 2. Password hashing (IMPORTANT)
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	$check = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE email='$email'");
	if (mysqli_num_rows($check) > 0) {
    		echo "<script> 
        		alert('An account with that email already exists. You may want to log in instead!'); 
        		window.history.back();
    	</script>";
    	exit();
}
	// 3. Insert into database
	$sql = "INSERT INTO iBayMembers (firstName, surname, email, password)
		VALUES ('$firstName', '$surname', '$email', '$hashedPassword')";

    	if (mysqli_query($conn, $sql)) {
        	echo "Account created successfully";
        	header("Location: /ibay/login.html");
        	exit();
    	} else {
        	echo "Error: " . mysqli_error($conn);
    	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="site-header">
        <?php include("includes/navbar.php"); ?>

    </header>

    <main class="auth-page">
        <section class="auth-card">
            <h1>Create your iBay account</h1>
            <p class="auth-subtitle">Register to buy, sell, and manage listings.</p>

            <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=420551119650-1vo8vduihfvnq3jrs0ii3etbl60kamr8.apps.googleusercontent.com&redirect_uri=http://localhost/ibay/google_callback.php&response_type=code&scope=email%20profile" class="google-button">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="G" width="18" height="18">
                Sign up with Google
            </a>

            <div class="auth-divider"><span>or</span></div>

            <form class="auth-form" id="signup-form" action="php/signup.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First name</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>

                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" id="surname" name="surname" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>

                <button type="submit" name="signup" class="primary-button">Create Account</button>

                <p class="auth-switch">
                    Already have an account?
                    <a href="login.html">Login</a>
                </p>
            </form>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>

    <script src="js/signup.js"></script>
</body>
</html>