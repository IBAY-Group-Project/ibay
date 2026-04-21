<?php
session_start();
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];
    $firstname = htmlspecialchars($_POST["firstname"]);
    $email = htmlspecialchars($_POST["email"]);
    $address = htmlspecialchars($_POST["address"]);
    $postcode = htmlspecialchars($_POST["postcode"]);

    // Check passwords match
    if ($password !== $confirm_password) {
        echo "<script> alert('Passwords do not match!'); window.history.back(); </script>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already taken
    $check_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check_user) > 0) {
        echo "<script> alert('Username already taken!'); window.history.back(); </script>";
        exit;
    }

    // Check if email already registered
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script> alert('Email already registered!'); window.history.back(); </script>";
        exit;
    }

    // Insert the new user - columns match your database order
    $sql = "INSERT INTO users (username, password, firstname, email, address, postcode) 
            VALUES ('$username', '$hashed_password', '$firstname', '$email', '$address', '$postcode')";

    if (mysqli_query($conn, $sql)) {
        echo "<script> 
            alert('Account created successfully!'); 
            document.location.href = 'login.html';
        </script>";
    } else {
        echo "<script> alert('Something went wrong: " . mysqli_error($conn) . "'); </script>";
    }
}
?>