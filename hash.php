<?php
$password = "1";
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo "INSERT INTO iBayMembers (email, password, username) VALUES ('admin@ibay.com', '$hashed', 'admin');";
?>
