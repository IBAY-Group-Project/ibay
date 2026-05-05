<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    $condition = $_POST['condition'] ?? '';
    $price = $_POST['price'] ?? '0';
    $postage = $_POST['postage'] ?? '';
    $description = $_POST['description'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    
    if (!empty($title) && !empty($category)) {
        $sql = "INSERT INTO iBayItems (userId, title, category, condition, price, postage, description, sold) 
                VALUES ('$userId', '$title', '$category', '$condition', '$price', '$postage', '$description', 0)";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: account.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

