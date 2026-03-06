<?php
$password = $_POST['password'];
$username = $_POST['username'];
$email = $_POST['email'];
$confirm_password = $_POST['confirm-password'];


if ($password !== $confirm_password) {
    echo "Passwords do not match. Please try again.";
    exit;
}
?>