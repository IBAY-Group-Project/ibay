<?php 
include("includes/check.php");
include("connection.php");


$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$condition = trim($_POST['condition'] ?? '');
$postcode = trim($_POST['postcode'] ?? '');
$price = trim($_POST['price'] ?? '');
$postage = trim($_POST['postcode'] ?? '');
$finish = trim($_POST['finish'] ?? '');
$description = trim($_POST['description'] ?? '');




