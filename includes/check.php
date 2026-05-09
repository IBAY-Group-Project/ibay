<?php
session_start();
 
$is_logged_in = isset($_SESSION['email']);
 
// If this page requires login, redirect if not logged in
if (isset($requireLogin) && $requireLogin && !$is_logged_in) {
    header("Location: login.php");
    exit();
}
?>
 