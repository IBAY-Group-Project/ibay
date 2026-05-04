<?php
require_once "db.php";

$result = $conn->query("SELECT 1");

if (!$result) {
    die("DB ERROR: " . $conn->error);
}

echo "DB CONNECTED AND WORKING";

require_once "db.php";

$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_row()) {
    echo $row[0] . "<br>";
}


$result = $conn->query("SELECT * FROM iBayItems LIMIT 30");

while ($row = $result->fetch_assoc()) {
    print_r($row);
    echo "<br><br>";
}

?>