<?php
require_once "db.php";

$result = $conn->query("SELECT 1");

if (!$result) {
    die("DB ERROR: " . $conn->error);
}

echo "DB CONNECTED AND WORKING";
?>