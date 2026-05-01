<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("connection.php");

if (isset($_POST['login'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE email='$email'");

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // Store user info in session
            $_SESSION['userId']   = $row['userId'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['email']     = $row['email'];

            // Redirect to homepage
            header("Location: ../index.php");
            exit();
        } else {
            echo "<script> alert('Incorrect password!'); window.history.back(); </script>";
        }
    } else {
        echo "<script> alert('Email not found!'); window.history.back(); </script>";
    }
}
?>