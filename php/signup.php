<?php
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];


if ($password !== $confirm_password) {
    echo "Passwords do not match. Please try again.";
    exit;
}
?>