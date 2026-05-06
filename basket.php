<?php
session_start();
include("php/connection.php");

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['userId'];

if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $itemId = (int)$_GET['add'];
    $check = mysqli_query($conn, "SELECT itemId FROM iBayItems WHERE itemId = '$itemId'");
    if (mysqli_num_rows($check) == 1) {
        $existing = mysqli_query($conn, "SELECT basketId, quantity FROM iBayBasket WHERE userId = '$userId' AND itemId = '$itemId'");
        if (mysqli_num_rows($existing) > 0) {
            $row = mysqli_fetch_assoc($existing);
            $newQty = $row['quantity'] + 1;
            mysqli_query($conn, "UPDATE iBayBasket SET quantity = '$newQty' WHERE basketId = '{$row['basketId']}'");
        } else {
            mysqli_query($conn, "INSERT INTO iBayBasket (userId, itemId, quantity) VALUES ('$userId', '$itemId', 1)");
        }
    }
    header("Location: basket.php");
    exit();
}

if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $basketId = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM iBayBasket WHERE basketId = '$basketId' AND userId = '$userId'");
    header("Location: basket.php");
    exit();
}

if (isset($_POST['update'])) {
    $basketId = (int)$_POST['basketId'];
    $quantity  = (int)$_POST['quantity'];
    if ($quantity < 1) $quantity = 1;
    mysqli_query($conn, "UPDATE iBayBasket SET quantity = '$quantity' WHERE basketId = '$basketId' AND userId = '$userId'");
    header("Location: basket.php");
    exit();
}

$basketResult = mysqli_query($conn, "
    SELECT b.basketId, b.quantity, i.itemId, i.title, i.price, i.postage, m.firstname, m.surname, img.image
    FROM iBayBasket b
    JOIN iBayItems i ON b.itemId = i.itemId
    JOIN iBayMembers m ON i.userId = m.userId
    LEFT JOIN iBayImages img ON i.itemId = img.itemId
    WHERE b.userId = '$userId'
    GROUP BY b.basketId
    ORDER BY b.addedAt DESC
");

$subtotal = 0;
$items    = [];
while ($row = mysqli_fetch_assoc($basketResult)) {
    $subtotal += $row['price'] * $row['quantity'];
    $items[]   = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Basket</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="js/main.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="top-header">
            <div class="logo">
                <a href="index.php">iBay</a>
            </div>
            <nav class="top-nav">
                <a href="sell.php">Sell</a>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="account.php">Account</a>
                    <a href="php/logout.php">Logout</a>
                <?php else: ?>
                    <a href="signup.html">Signup</a>
                    <a href="login.html">Login</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search for items...">
                    <button type="submit" class="search-submit-button">Search</button>
                </form>
                <a href="account.php" class="icon-button"><i class="fa-solid fa-user"></i></a>
                <a href="basket.php" class="icon-button"><i class="fa-solid fa-basket-shopping"></i></a>
            </div>
        </div>
        <nav class="category-nav">
            <a href="search.php?category=Technology">Technology</a>
            <a href="search.php?category=Clothing">Clothing</a>
            <a href="search.php?category=Trading Cards">Trading Cards</a>
            <a href="search.php?category=Gardening">Gardening</a>
            <a href="search.php?category=Home">Home</a>
            <a href="search.php?category=Collectables">Collectables</a>
            <a href="search.php?category=Sports">Sports</a>
            <a href="search.php?category=Books">Books</a>
        </nav>
    </header>

    <main class="basket-page">
        <section class="basket-layout">

            <div class="basket-items-card">
                <h1>Your Basket</h1>

                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $imageSrc = $item['image'] ? 'images/products/' . htmlspecialchars($item['image']) : 'images/placeholder.jpg';
                        $title    = htmlspecialchars($item['title']);
                        $seller   = htmlspecialchars($item['firstname'] . ' ' . $item['surname']);
                        $price    = number_format($item['price'], 2);
                        $total    = number_format($item['price'] * $item['quantity'], 2);
                        ?>
                        <div class="basket-item">
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $title; ?>" class="basket-item-image">
                            <div class="basket-item-info">
                                <h2><?php echo $title; ?></h2>
                                <p>Seller: <?php echo $seller; ?></p>
                                <p>Postage: <?php echo htmlspecialchars($item['postage']); ?></p>
                                <p class="basket-item-price">&pound;<?php echo $price; ?> each</p>
                                <form method="POST" action="basket.php" style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                                    <input type="hidden" name="basketId" value="<?php echo $item['basketId']; ?>">
                                    <label>Qty:</label>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10" style="width: 60px; padding: 6px; border: 1px solid #ccc; border-radius: 8px;">
                                    <button type="submit" name="update" class="secondary-button" style="padding: 6px 12px; font-size: 0.85rem;">Update</button>
                                </form>
                            </div>
                            <a href="basket.php?remove=<?php echo $item['basketId']; ?>" class="secondary-button basket-remove-button">Remove</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="fa-solid fa-basket-shopping" style="font-size: 3rem; color: #ccc; margin-bottom: 16px; display: block;"></i>
                        <h3>Your basket is empty</h3>
                        <p style="color: #666; margin-top: 8px;">Browse listings and add items to your basket</p>
                        <a href="index.php" class="primary-button" style="width: auto; display: inline-block; margin-top: 20px; padding: 12px 24px;">Continue Shopping</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (count($items) > 0): ?>
            <aside class="basket-summary-card">
                <h2>Summary</h2>

                <?php foreach ($items as $item): ?>
                    <div class="basket-summary-row">
                        <span><?php echo htmlspecialchars($item['title']); ?> x<?php echo $item['quantity']; ?></span>
                        <span>&pound;<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="basket-summary-row">
                    <span>Subtotal</span>
                    <span>&pound;<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="basket-summary-row total-row">
                    <span>Total</span>
                    <span>&pound;<?php echo number_format($subtotal, 2); ?></span>
                </div>

                <a href="checkout.php" class="primary-button" style="margin-top: 16px; display: block; text-align: center;">Proceed to Checkout</a>
                <a href="index.php" class="secondary-button" style="margin-top: 10px; display: block; text-align: center; padding: 12px;">Continue Shopping</a>
            </aside>
            <?php endif; ?>

        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>