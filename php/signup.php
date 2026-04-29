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

	// 3. Insert into database
	$sql = "INSERT INTO iBayMembers (firstName, surname, email, password)
		VALUES ('$firstName', '$surname', '$email', '$hashedPassword')";

    	if (mysqli_query($conn, $sql)) {
        	echo "Account created successfully";
        	header("Location: /login.html");
        	exit();
    	} else {
        	echo "Error: " . mysqli_error($conn);
    	}
}
?>
