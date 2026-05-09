<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];

$sql = "SELECT b.basketId, b.quantity, i.itemId, i.title, i.price, i.postage, m.username, img.image
        FROM iBayBasket b
        JOIN iBayItems i ON b.itemId = i.itemId
        JOIN iBayMembers m ON i.userId = m.userId
        LEFT JOIN iBayImages img ON i.itemId = img.itemId
        WHERE b.userId = $userId
        GROUP BY b.basketId";

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
    </header>

    <main class="basket-page">
        <section class="basket-layout">
            <div class="basket-items-card">
                <h1>Your Basket</h1>

                <?php 
                $subtotal = 0;
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
                            $bImg = $item['image'] ?? '';
                            $bSrc = $bImg ? (str_starts_with($bImg, 'http') ? $bImg : 'images/products/' . htmlspecialchars($bImg)) : 'images/placeholder-product.jpg';
                        ?>
                        <img src="<?= $bSrc ?>" alt="Basket item image" class="basket-item-image">

                        <div class="basket-item-info">
                            <h2><?= htmlspecialchars($item['title']) ?></h2>
                            <p>Seller: <?= htmlspecialchars($item['username']) ?></p>
                            <p class="basket-item-price">£<?= number_format($itemTotal, 2) ?></p>
                            <p>Qty: <?= $item['quantity'] ?></p>
                        </div>


                        <div class="basket-item-controls">
                            <div class="quantity-controls">
                                <form action="modify_basket.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="decrease">
                                    <input type="hidden" name="basketId" value="<?= $item['basketId'] ?>">
                                    <button type="submit" class="qty-btn">−</button>
                                </form>
                                <span><?= $item['quantity'] ?></span>
                                <form action="modify_basket.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="increase">
                                    <input type="hidden" name="basketId" value="<?= $item['basketId'] ?>">
                                    <button type="submit" class="qty-btn">+</button>
                                </form>
                            </div>

                            <form action="modify_basket.php" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="basketId" value="<?= $item['basketId'] ?>">
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
                    <span>£<?= number_format($subtotal, 2) ?></span>
                </div>
 
                <div class="basket-summary-row">
                    <span>Postage</span>
                    <span>£<?= number_format($totalPostage, 2) ?></span>
                </div>
 
                <div class="basket-summary-row total-row">
                    <span>Total</span>
                    <span>£<?= number_format($subtotal + $totalPostage, 2) ?></span>
                </div>
 
                <a href="checkout.php" class="primary-button">Proceed to Checkout</a>
                <a href="index.php" class="secondary-button" >Continue Shopping</a>
            </aside>
            <?php endif; ?>
        </section>
    </main>

   <?php include("includes/footer.php"); ?>

</body>
</html>