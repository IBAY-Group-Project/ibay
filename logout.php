<?php
session_start();
session_destroy();
header("Location: /iBay/main-G01.php");
exit();
?>