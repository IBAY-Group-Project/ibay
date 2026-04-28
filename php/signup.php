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

    // 2. Password hashing (IMPORTANT)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 3. Insert into database
    $sql = "INSERT INTO ibayMembers (firstName, surname, email, password)
            VALUES ('$firstName', '$surname', '$email', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
        echo "Account created successfully";
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
