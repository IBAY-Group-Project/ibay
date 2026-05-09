<?php
$requireLogin = true;
include("includes/check.php");
include("includes/db.php");

$userId = $_SESSION['userId'];

$sql = "SELECT b.basketId, b.quantity, i.itemId, i.title, i.price, i.postage, m.username 
        FROM iBayBasket b 
        JOIN iBayItems i ON b.itemId = i.itemId 
        JOIN iBayMembers m ON i.userId = m.userId 
        WHERE b.userId = $userId";

$result = mysqli_query($conn, $sql);
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
        <?php include("includes/navbar.php"); ?>
        </div>
    </header>

    <main class="basket-page">
        <section class="basket-layout">
            <div class="basket-items-card">
                <h1>Your Basket</h1>

                <?php
                $subtotal     = 0;
                $totalPostage = 0;
                $hasItems     = mysqli_num_rows($result) > 0;

                if ($hasItems):
                    while ($item = mysqli_fetch_assoc($result)):
                        $itemTotal  = $item['price'] * $item['quantity'];
                        $subtotal  += $itemTotal;

                        $postageRaw = $item['postage'];
                        if (stripos($postageRaw, 'free') !== false || stripos($postageRaw, 'collection') !== false) {
                            $postageAmount = 0;
                        } else {
                            $postageAmount = (float)preg_replace('/[^0-9.]/', '', $postageRaw);
                        }
                        $totalPostage += $postageAmount;
                ?>
                    <div class="basket-item">
                        <?php
                        $imgCheck = mysqli_query($conn, "SELECT image FROM iBayImages WHERE itemId = {$item['itemId']} LIMIT 1");
                        $imgSrc   = 'images/placeholder.jpg';
                        if ($imgCheck && mysqli_num_rows($imgCheck) > 0) {
                            $imgRow = mysqli_fetch_assoc($imgCheck);
                            $imgSrc = 'images/products/' . htmlspecialchars($imgRow['image']);
                        }
                        ?>
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="basket-item-image">

                        <div class="basket-item-info">
                            <h2><?php echo htmlspecialchars($item['title']); ?></h2>
                            <p>Seller: <?php echo htmlspecialchars($item['username']); ?></p>
                            <p>Postage: <?php echo htmlspecialchars($item['postage']); ?></p>
                            <p class="basket-item-price">&pound;<?php echo number_format($itemTotal, 2); ?></p>
                            <p>Qty: <?php echo $item['quantity']; ?></p>
                        </div>

                        <div class="basket-item-controls">
                            <div class="quantity-controls">
                                <form action="modify_basket.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action"   value="decrease">
                                    <input type="hidden" name="basketId" value="<?php echo $item['basketId']; ?>">
                                    <button type="submit" class="qty-btn">&#8722;</button>
                                </form>
                                <span><?php echo $item['quantity']; ?></span>
                                <form action="modify_basket.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action"   value="increase">
                                    <input type="hidden" name="basketId" value="<?php echo $item['basketId']; ?>">
                                    <button type="submit" class="qty-btn">+</button>
                                </form>
                            </div>
                            <form action="modify_basket.php" method="post" style="display:inline;">
                                <input type="hidden" name="action"   value="remove">
                                <input type="hidden" name="basketId" value="<?php echo $item['basketId']; ?>">
                                <button type="submit" class="secondary-button basket-remove-button">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <div style="text-align:center; padding:60px 20px;">
                        <i class="fa-solid fa-basket-shopping" style="font-size:3rem;color:#ccc;margin-bottom:16px;display:block;"></i>
                        <h3>Your basket is empty</h3>
                        <p style="color:#666;margin-top:8px;">Browse listings and add items to your basket</p>
                        <a href="index.php" class="primary-button" style="width:auto;display:inline-block;margin-top:20px;padding:12px 24px;">Continue Shopping</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($hasItems): ?>
            <aside class="basket-summary-card">
                <h2>Summary</h2>
                <div class="basket-summary-row">
                    <span>Subtotal</span>
                    <span>&pound;<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="basket-summary-row">
                    <span>Postage</span>
                    <span>&pound;<?php echo number_format($totalPostage, 2); ?></span>
                </div>
                <div class="basket-summary-row total-row">
                    <span>Total</span>
                    <span>&pound;<?php echo number_format($subtotal + $totalPostage, 2); ?></span>
                </div>
                <a href="checkout.php" class="primary-button" style="margin-top:16px;display:block;text-align:center;">Proceed to Checkout</a>
                <a href="index.php" class="secondary-button" style="margin-top:10px;display:block;text-align:center;padding:12px;">Continue Shopping</a>
            </aside>
            <?php endif; ?>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>
</body>
</html>