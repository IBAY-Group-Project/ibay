<?php
include("includes/check.php");
include("includes/db.php");

$orderId = $_GET['orderId'] ?? null;

if (!$orderId) {
    header("Location: index.php");
    exit();
}

$query = "SELECT s.*, i.title, m.username
FROM iBaySales s
JOIN iBayItems i ON s.itemId = i.itemId
JOIN iBayMembers m ON s.sellerId = m.userId
WHERE s.orderId = ? AND s.buyerId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $orderId, $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}



$orderQuery = "SELECT * FROM iBayOrders WHERE orderId = ? AND buyerId = ?";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("ii", $orderId, $_SESSION['userId']);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - iBay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">
    <?php include("includes/navbar.php"); ?>
</header>

<main class="checkout-main">
    <section class="checkout-title">
        <h1>Order Confirmed</h1>
    </section>

    <div class="order-success">
        <div class="success-message">
            <h2>Thank you for your order!</h2>
            <p>Order ID: <strong><?php echo $orderId; ?></strong></p>
        </div>

        <section class="order-details">
            <h3>Order Items</h3>
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="success-item">
                    <p><strong><?php echo htmlspecialchars($item['username']); ?></strong></p>
                    <p>Title: <?php echo $item['title']; ?></p>
                    <p>Qty: <?php echo $item['quantity']; ?></p>
                    <p>Price: £<?php echo number_format($item['finalPrice'], 2); ?></p>
                    <p>Postage: £<?php echo number_format($item['itemPostage'], 2); ?></p>
                    

                </div>
            <?php endwhile; ?>
        </section>

        <section class="order-summary">
            <h3>Summary</h3>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>£<?= number_format($order['orderTotal'], 2) ?></span>
            </div>

            <div class="summary-row">
                <span>Postage:</span>
                <span>£<?= number_format($order['totalPostage'], 2) ?></span>
            </div>

            <div class="summary-row total">
                <span>Total:</span>
                <span>£<?= number_format($order['orderTotal'] + $order['totalPostage'], 2) ?></span>
            </div>
        </section>

        <div class="success-actions">
            <a href="index.php" class="button">Continue Shopping</a>
        </div>
    </div>
</main>

<footer>
    <div class="footer">
        &copy; 2026 iBay Marketplace. All rights reserved.
    </div>
</footer>

</body>
</html>
