<?php
$password = "test";
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo "UPDATE iBayMembers SET password='$hashed' WHERE email='test@ibay.com';";
?>
