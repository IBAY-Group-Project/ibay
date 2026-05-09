<?php
$requireLogin = true;
include("includes/check.php");
include("includes/db.php");

$userId = $_SESSION['userId'];
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $itemId = (int)($_POST['itemId'] ?? 0);
    if ($itemId > 0) {
        $sql = "INSERT INTO iBayBasket (userId, itemId, quantity, addedAt) 
                VALUES ($userId, $itemId, 1, NOW())
                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        mysqli_query($conn, $sql);
    }

} elseif ($action === 'remove') {
    $basketId = (int)($_POST['basketId'] ?? 0);
    mysqli_query($conn, "UPDATE iBayBasket SET quantity = quantity - 1 WHERE basketId = $basketId AND userId = $userId");
    mysqli_query($conn, "DELETE FROM iBayBasket WHERE basketId = $basketId AND quantity <= 0");

} elseif ($action === 'increase') {
    $basketId = (int)($_POST['basketId'] ?? 0);
    mysqli_query($conn, "UPDATE iBayBasket SET quantity = quantity + 1 WHERE basketId = $basketId AND userId = $userId");

} elseif ($action === 'decrease') {
    $basketId = (int)($_POST['basketId'] ?? 0);
    mysqli_query($conn, "UPDATE iBayBasket SET quantity = quantity - 1 WHERE basketId = $basketId AND userId = $userId");
    mysqli_query($conn, "DELETE FROM iBayBasket WHERE basketId = $basketId AND quantity <= 0");
}

header("Location: basket.php");
exit();
?>