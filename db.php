<?php
$host = "localhost";
$db   = "group01";
$user = "group01";
$pass = "Juvwusorn7rhFvEEdMaX";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
?>