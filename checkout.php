<?php
session_start();
include("php/connection.php");

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}

$userId   = $_SESSION['userId'];
$success  = false;
$error    = '';
$buyNowId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

$buyerResult = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE userId = '$userId'");
$buyer       = mysqli_fetch_assoc($buyerResult);

if (isset($_POST['confirm'])) {
    $address  = mysqli_real_escape_string($conn, trim($_POST['address']));
    $postcode = mysqli_real_escape_string($conn, trim($_POST['postcode']));

    if (empty($address) || empty($postcode)) {
        $error = "Please enter a delivery address and postcode before confirming.";
    } else {
        if ($buyNowId) {
            $itemResult = mysqli_query($conn, "SELECT * FROM iBayItems WHERE itemId = '$buyNowId' AND sold = 0");
            if (mysqli_num_rows($itemResult) == 0) {
                $error = "This item is no longer available.";
            } else {
                $item       = mysqli_fetch_assoc($itemResult);
                $sellerId   = $item['userId'];
                $finalPrice = $item['price'];
                $newRating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

                if ($newRating < 1 || $newRating > 5) {
                    $error = "Please give the seller a rating between 1 and 5 before confirming.";
                } else {
                    mysqli_query($conn, "UPDATE iBayMembers SET address='$address', postcode='$postcode' WHERE userId='$userId'");
                    mysqli_query($conn, "INSERT INTO iBaySales (itemId, buyerId, sellerId, finalPrice, saleDate) VALUES ('$buyNowId', '$userId', '$sellerId', '$finalPrice', NOW())");
                    mysqli_query($conn, "UPDATE iBayItems SET sold = 1 WHERE itemId = '$buyNowId'");

                    $sellerResult  = mysqli_query($conn, "SELECT rating FROM iBayMembers WHERE userId = '$sellerId'");
                    $sellerRow     = mysqli_fetch_assoc($sellerResult);
                    $currentRating = $sellerRow['rating'];
                    $salesCount    = mysqli_query($conn, "SELECT COUNT(*) as count FROM iBaySales WHERE sellerId = '$sellerId'");
                    $salesRow      = mysqli_fetch_assoc($salesCount);
                    $totalSales    = $salesRow['count'];
                    $newAvg        = ($currentRating == 0 || $totalSales <= 1) ? $newRating : round((($currentRating * ($totalSales - 1)) + $newRating) / $totalSales, 1);

                    mysqli_query($conn, "UPDATE iBayMembers SET rating = '$newAvg' WHERE userId = '$sellerId'");
                    $success = true;
                }
            }
        } else {
            $basketResult = mysqli_query($conn, "
                SELECT b.*, i.userId as sellerId, i.price, i.sold, i.itemId as bItemId
                FROM iBayBasket b
                JOIN iBayItems i ON b.itemId = i.itemId
                WHERE b.userId = '$userId'
            ");

            if (mysqli_num_rows($basketResult) == 0) {
                $error = "Your basket is empty.";
            } else {
                $allGood = true;
                $rows    = [];
                while ($row = mysqli_fetch_assoc($basketResult)) {
                    if ($row['sold'] == 1) { $error = "One or more items in your basket have already been sold."; $allGood = false; break; }
                    $rows[] = $row;
                }

                if ($allGood) {
                    $sellerIds = array_unique(array_column($rows, 'sellerId'));
                    foreach ($sellerIds as $sid) {
                        $ratingVal = isset($_POST['rating_' . $sid]) ? (int)$_POST['rating_' . $sid] : 0;
                        if ($ratingVal < 1 || $ratingVal > 5) { $error = "Please rate all sellers before confirming."; $allGood = false; break; }
                    }
                }

                if ($allGood) {
                    mysqli_query($conn, "UPDATE iBayMembers SET address='$address', postcode='$postcode' WHERE userId='$userId'");
                    foreach ($rows as $row) {
                        $itemId     = $row['bItemId'];
                        $sellerId   = $row['sellerId'];
                        $finalPrice = $row['price'] * $row['quantity'];
                        mysqli_query($conn, "INSERT INTO iBaySales (itemId, buyerId, sellerId, finalPrice, saleDate) VALUES ('$itemId', '$userId', '$sellerId', '$finalPrice', NOW())");
                        mysqli_query($conn, "UPDATE iBayItems SET sold = 1 WHERE itemId = '$itemId'");

                        $newRating     = (int)$_POST['rating_' . $sellerId];
                        $sellerResult  = mysqli_query($conn, "SELECT rating FROM iBayMembers WHERE userId = '$sellerId'");
                        $sellerRow     = mysqli_fetch_assoc($sellerResult);
                        $currentRating = $sellerRow['rating'];
                        $salesCount    = mysqli_query($conn, "SELECT COUNT(*) as count FROM iBaySales WHERE sellerId = '$sellerId'");
                        $salesRow      = mysqli_fetch_assoc($salesCount);
                        $totalSales    = $salesRow['count'];
                        $newAvg        = ($currentRating == 0 || $totalSales <= 1) ? $newRating : round((($currentRating * ($totalSales - 1)) + $newRating) / $totalSales, 1);
                        mysqli_query($conn, "UPDATE iBayMembers SET rating = '$newAvg' WHERE userId = '$sellerId'");
                    }
                    mysqli_query($conn, "DELETE FROM iBayBasket WHERE userId = '$userId'");
                    $success = true;
                }
            }
        }
    }
}

$buyerResult = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE userId = '$userId'");
$buyer       = mysqli_fetch_assoc($buyerResult);

if ($buyNowId) {
    $summaryResult = mysqli_query($conn, "
        SELECT i.*, img.image, m.firstname, m.surname, m.userId as sellerId
        FROM iBayItems i
        LEFT JOIN iBayImages img ON i.itemId = img.itemId
        JOIN iBayMembers m ON i.userId = m.userId
        WHERE i.itemId = '$buyNowId'
        GROUP BY i.itemId
    ");
    $summaryItems = [];
    while ($row = mysqli_fetch_assoc($summaryResult)) { $row['quantity'] = 1; $summaryItems[] = $row; }
} else {
    $summaryResult = mysqli_query($conn, "
        SELECT i.*, b.quantity, img.image, m.firstname, m.surname, m.userId as sellerId
        FROM iBayBasket b
        JOIN iBayItems i ON b.itemId = i.itemId
        LEFT JOIN iBayImages img ON i.itemId = img.itemId
        JOIN iBayMembers m ON i.userId = m.userId
        WHERE b.userId = '$userId'
        GROUP BY b.basketId
    ");
    $summaryItems = [];
    while ($row = mysqli_fetch_assoc($summaryResult)) { $summaryItems[] = $row; }
}

$sellers = [];
foreach ($summaryItems as $item) {
    $sid = $item['sellerId'];
    if (!isset($sellers[$sid])) $sellers[$sid] = $item['firstname'] . ' ' . $item['surname'];
}

$total = 0;
foreach ($summaryItems as $item) { $total += $item['price'] * $item['quantity']; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Checkout</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="top-header">
            <div class="logo"><a href="index.php">iBay</a></div>
            <nav class="top-nav">
                <a href="sell.php">Sell</a>
                <a href="account.php">Account</a>
                <a href="php/logout.php">Logout</a>
            </nav>
            <div class="header-actions">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search for items...">
                    <button type="submit" class="search-submit-button">Search</button>
                </form>
                <a href="account.php" class="icon-button">&#128100;</a>
                <a href="basket.php" class="icon-button">&#128722;</a>
            </div>
        </div>
    </header>

    <main class="basket-page">
        <?php if ($success): ?>
            <div class="order-confirmed">
                <div class="order-confirmed-box">
                    <div class="order-confirmed-icon">&#10003;</div>
                    <h1>Order Confirmed!</h1>
                    <p>Thank you for your purchase. Your order has been placed successfully.</p>
                    <p>Total paid: <strong>&pound;<?php echo number_format($total, 2); ?></strong></p>
                    <div class="order-confirmed-actions">
                        <a href="index.php" class="primary-button" style="width:auto;padding:12px 24px;">Continue Shopping</a>
                        <a href="account.php" class="secondary-button" style="width:auto;padding:12px 24px;">My Account</a>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="basket-layout">
                <div class="basket-items-card">
                    <h1>Checkout</h1>
                    <?php if ($error): ?>
                        <div class="alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php foreach ($summaryItems as $item): ?>
                        <?php $imageSrc = $item['image'] ? 'images/products/' . htmlspecialchars($item['image']) : 'images/placeholder.jpg'; ?>
                        <div class="basket-item">
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="basket-item-image">
                            <div class="basket-item-info">
                                <h2><?php echo htmlspecialchars($item['title']); ?></h2>
                                <p>Seller: <?php echo htmlspecialchars($item['firstname'] . ' ' . $item['surname']); ?></p>
                                <p>Postage: <?php echo htmlspecialchars($item['postage']); ?></p>
                                <p>Qty: <?php echo $item['quantity']; ?></p>
                                <p class="basket-item-price">&pound;<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <aside class="basket-summary-card">
                    <h2>Order Summary</h2>
                    <div class="basket-summary-row">
                        <span>Subtotal</span>
                        <span>&pound;<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="basket-summary-row total-row">
                        <span>Total</span>
                        <span>&pound;<?php echo number_format($total, 2); ?></span>
                    </div>

                    <form method="POST" action="checkout.php<?php echo $buyNowId ? '?id=' . $buyNowId : ''; ?>">
                        <div class="address-block">
                            <h3>Delivery Address</h3>
                            <label for="address">Street Address</label>
                            <input type="text" id="address" name="address" placeholder="Enter your delivery address" value="<?php echo htmlspecialchars($buyer['address'] ?? ''); ?>" required>
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" placeholder="Enter your postcode" value="<?php echo htmlspecialchars($buyer['postcode'] ?? ''); ?>" required>
                            <?php if (!empty($buyer['address'])): ?>
                                <p style="font-size:0.82rem;color:#1f3f8f;margin-top:4px;">&#10003; Pre-filled from your saved address. Update if needed.</p>
                            <?php endif; ?>
                        </div>

                        <?php foreach ($sellers as $sid => $sellerName): ?>
                            <div class="rating-block">
                                <h3>Rate seller: <?php echo htmlspecialchars($sellerName); ?></h3>
                                <p>How would you rate this seller? (required)</p>
                                <div class="star-rating" id="stars-<?php echo $sid; ?>">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" name="<?php echo $buyNowId ? 'rating' : 'rating_' . $sid; ?>" id="star_<?php echo $sid . '_' . $i; ?>" value="<?php echo $i; ?>">
                                        <label for="star_<?php echo $sid . '_' . $i; ?>">&#9733;</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <button type="submit" name="confirm" class="primary-button" style="margin-top:16px;">Confirm Order</button>
                    </form>

                    <a href="<?php echo $buyNowId ? 'item.php?id=' . $buyNowId : 'basket.php'; ?>" class="secondary-button" style="display:block;text-align:center;margin-top:10px;padding:12px;">
                        &#8592; <?php echo $buyNowId ? 'Back to Item' : 'Back to Basket'; ?>
                    </a>
                </aside>
            </div>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>