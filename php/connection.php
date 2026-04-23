<?php
$conn = mysqli_connect("localhost", "root", "", "ibay");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Successful Connection to MySQL";
}
?>