<?php
session_start();
session_destroy();
header("Location: /ibay/index.php");
exit();
?>