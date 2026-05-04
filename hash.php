<?php
$password = "1";
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo "INSERT INTO iBayMembers (email, password, username) VALUES ('1', '$hashed', '1');";
?>
