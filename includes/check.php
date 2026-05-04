<?php
session_start();
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

$is_logged_in = isset($_SESSION['email']);
?>
