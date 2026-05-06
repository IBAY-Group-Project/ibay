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
    <meta name="viewport" content="width=device-width, 1f3f8finitial-scale=1.0">
    <title>Order Confirmed - iBay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">
    <?php include("includes/navbar.php"); ?>
</header>

<main class="order-success-page">
    <div class="order-success">

        <div class="success-message">
            <h1>Order Confirmed</h1>
            <p>Thank you for your purchase. Your order has been placed successfully.</p>
            <p class="success-meta">Order <strong>#<?= $orderId ?></strong> &nbsp;·&nbsp; <?= date('d M Y', strtotime($order['orderDate'])) ?> &nbsp;·&nbsp; <?= htmlspecialchars($order['paymentMethod']) ?></p>
        </div>

        <section class="order-details">
            <h2>Items</h2>
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="success-item">
                    <div class="success-item-image">📦</div>
                    <div class="success-item-info">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p>Sold by <strong><?= htmlspecialchars($item['username']) ?></strong></p>
                        <p>Qty: <?= $item['quantity'] ?></p>
                    </div>
                    <div class="success-item-price">
                        <strong>£<?= number_format($item['finalPrice'], 2) ?></strong>
                        <span>+ £<?= number_format($item['itemPostage'], 2) ?> postage</span>
                    </div>
                </div>
            <?php endwhile; ?>
        </section>

        <section class="order-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>£<?= number_format($order['orderTotal'], 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Postage</span>
                <span>£<?= number_format($order['totalPostage'], 2) ?></span>
            </div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span>£<?= number_format($order['orderTotal'] + $order['totalPostage'], 2) ?></span>
            </div>
        </section>

        <div class="success-actions">
            <a href="orders.php" class="secondary-button" id="view-orders-btn">View All Orders</a>
            <a href="index.php" class="primary-button" id="continue-shopping-btn">Continue Shopping</a>
        </div>

    </div>
</main>

<footer class="site-footer">
    <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
</footer>

</body>
</html>
