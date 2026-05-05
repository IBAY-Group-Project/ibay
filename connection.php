<?php
$conn = mysqli_connect(
    "sci-project.lboro.ac.uk",
    "group01",
    "Juvwusorn7rhFvEEdMaX",
    "group01"
);


mysqli_set_charset($conn, "utf8mb4");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>