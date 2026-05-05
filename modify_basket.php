<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];
$action = $_POST['action'];

if ($action == 'add') {
    $itemId = $_POST['itemId'];
    $sql = "INSERT INTO iBayBasket (userId, itemId, quantity, addedAt) 
            VALUES ($userId, $itemId, 1, NOW())
            ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    mysqli_query($conn, $sql);

} elseif ($action == 'remove') {
    $basketId = $_POST['basketId'];
    $sql = "UPDATE iBayBasket SET quantity = quantity - 1 WHERE basketId = $basketId";
    mysqli_query($conn, $sql);
    
    // Delete row if quantity drops to 0
    $sql2 = "DELETE FROM iBayBasket WHERE basketId = $basketId AND quantity <= 0";
    mysqli_query($conn, $sql2);


} elseif ($action == 'increase') {
    $basketId = $_POST['basketId'];
    $sql = "UPDATE iBayBasket SET quantity = quantity + 1 WHERE basketId = $basketId";
    mysqli_query($conn, $sql);


} elseif ($action == 'decrease') {
    $basketId = $_POST['basketId'];
    $sql = "UPDATE iBayBasket SET quantity = quantity - 1 WHERE basketId = $basketId";
    mysqli_query($conn, $sql);
    $sql2 = "DELETE FROM iBayBasket WHERE basketId = $basketId AND quantity <= 0";
    mysqli_query($conn, $sql2);
}
header("Location: basket.php");
?>
