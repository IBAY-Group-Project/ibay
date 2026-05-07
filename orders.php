<?php
include("includes/check.php");
require("includes/db.php");

$userId        = $_SESSION['userId'];
$ratingSuccess = false;

// Add buyer_rating column if it doesn't exist yet
$colCheck = $conn->query("SHOW COLUMNS FROM iBaySales LIKE 'buyer_rating'");
if ($colCheck && $colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE iBaySales ADD COLUMN buyer_rating TINYINT DEFAULT NULL");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate_seller'])) {
    $rateOrderId  = (int)($_POST['orderId']  ?? 0);
    $rateSellerId = (int)($_POST['sellerId'] ?? 0);
    $ratingVal    = isset($_POST['rating_' . $rateSellerId]) ? (int)$_POST['rating_' . $rateSellerId] : 0;

    if ($ratingVal >= 1 && $ratingVal <= 5 && $rateOrderId && $rateSellerId) {
        $upd = $conn->prepare("UPDATE iBaySales SET buyer_rating = ? WHERE orderId = ? AND sellerId = ? AND buyerId = ?");
        $upd->bind_param("iiii", $ratingVal, $rateOrderId, $rateSellerId, $userId);
        $upd->execute();
        if ($upd->affected_rows > 0) {
            $avgRow = $conn->query("SELECT AVG(buyer_rating) as avg_r FROM iBaySales WHERE sellerId = $rateSellerId AND buyer_rating IS NOT NULL")->fetch_assoc();
            $newAvg = round($avgRow['avg_r'], 1);
            $conn->query("UPDATE iBayMembers SET rating = $newAvg WHERE userId = $rateSellerId");
            $ratingSuccess = true;
        }
    }
}

$count_sql = "SELECT COUNT(*) as total FROM iBayOrders WHERE buyerId = $userId";
$count_result = mysqli_query($conn, $count_sql);
$total_orders = mysqli_fetch_assoc($count_result)['total'];

$items_per_page = 8;
$total_pages = max(1, ceil($total_orders / $items_per_page));
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;
$offset = ($current_page - 1) * $items_per_page;

$sql = "SELECT * FROM iBayOrders WHERE buyerId = $userId ORDER BY orderDate DESC LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - My Orders</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

<header class="site-header">
    <?php include("includes/navbar.php"); ?>
</header>

<main class="search-page">
    <section class="search-topbar">
        <div>
            <h1>My Orders</h1>
            <p class="search-subtitle">View your past purchases and order history.</p>
        </div>
    </section>

    <section class="search-results-card" style="width:100%;">
        <div class="search-results-header">
            <p class="results-count">Showing <?= mysqli_num_rows($result) ?> of <?= $total_orders ?> orders (Page <?= $current_page ?> of <?= $total_pages ?>)</p>
        </div>

        <?php if ($ratingSuccess): ?>
            <div class="alert-success" style="margin-bottom:12px;">Rating submitted — thank you!</div>
        <?php endif; ?>

        <div class="search-results-grid">
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <div class="order-card-wrapper">
                <a href="order_success.php?orderId=<?= $order['orderId'] ?>" class="search-item-card">
                    <div class="search-item-image">🧾</div>
                    <div class="search-item-content">
                        <h3>Order #<?= $order['orderId'] ?></h3>
                        <p><?= date('d M Y', strtotime($order['orderDate'])) ?> &middot; <?= htmlspecialchars($order['paymentMethod']) ?></p>
                        <?php if (!empty($order['deliveryDate'])): ?>
                            <p class="order-card-delivery">Arriving <?= date('d M Y', strtotime($order['deliveryDate'])) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($order['shippingAddress'])): ?>
                            <p class="order-card-address"><?= htmlspecialchars($order['shippingAddress']) ?>, <?= htmlspecialchars($order['postcode'] ?? '') ?></p>
                        <?php endif; ?>
                        <strong>£<?= number_format($order['orderTotal'] + $order['totalPostage'], 2) ?></strong>
                    </div>
                </a>
                <?php
                $ss = $conn->prepare("SELECT s.sellerId, m.firstname, m.surname, MAX(s.buyer_rating) as my_rating FROM iBaySales s JOIN iBayMembers m ON s.sellerId = m.userId WHERE s.orderId = ? AND s.buyerId = ? GROUP BY s.sellerId, m.firstname, m.surname");
                $ss->bind_param("ii", $order['orderId'], $userId);
                $ss->execute();
                $orderSellers = $ss->get_result();
                if ($orderSellers->num_rows > 0):
                ?>
                <details class="order-rating-toggle">
                    <summary>Rate Sellers</summary>
                    <?php while ($seller = $orderSellers->fetch_assoc()): ?>
                    <form method="POST">
                        <input type="hidden" name="rate_seller" value="1">
                        <input type="hidden" name="orderId"    value="<?= $order['orderId'] ?>">
                        <input type="hidden" name="sellerId"   value="<?= $seller['sellerId'] ?>">
                        <div class="rating-block" style="margin-top:8px;">
                            <h3>Rate <?= htmlspecialchars($seller['firstname'] . ' ' . $seller['surname']) ?></h3>
                            <div class="star-rating" id="stars-<?= $order['orderId'] ?>_<?= $seller['sellerId'] ?>">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <input type="radio" name="rating_<?= $seller['sellerId'] ?>" id="star_<?= $order['orderId'] ?>_<?= $seller['sellerId'] ?>_<?= $i ?>" value="<?= $i ?>" <?= ($seller['my_rating'] == $i) ? 'checked' : '' ?>>
                                    <label for="star_<?= $order['orderId'] ?>_<?= $seller['sellerId'] ?>_<?= $i ?>">&#9733;</label>
                                <?php endfor; ?>
                            </div>
                            <button type="submit" class="primary-button" style="margin-top:8px; width:auto; padding:6px 16px; font-size:0.85rem;">Submit</button>
                        </div>
                    </form>
                    <?php endwhile; ?>
                </details>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="pagination-bar">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>" class="secondary-button pagination-button">Previous</a>
            <?php else: ?>
                <button class="secondary-button pagination-button" disabled>Previous</button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <button class="pagination-number active-page"><?= $i ?></button>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="pagination-number"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>" class="secondary-button pagination-button">Next</a>
            <?php else: ?>
                <button class="secondary-button pagination-button" disabled>Next</button>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer class="site-footer">
    <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
</footer>

</body>
</html>
