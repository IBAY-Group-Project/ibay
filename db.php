<?php

$host = "YOUR_HOST_FROM_UNI";   // important
$db   = "group01";
$user = "YOUR_USERNAME";
$pass = "YOUR_PASSWORD";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "DB connected successfully";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>