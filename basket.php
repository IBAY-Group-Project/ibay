<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];

$sql = "SELECT b.basketId, b.quantity, i.itemId, i.title, i.price,i.postage, m.username 
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
    <title>iBay - Home</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

    <header class="site-header">

        <?php include("includes/navbar.php"); ?>
            </div>
        </div>
    </header>

    <main class="basket-page">
        <section class="basket-layout">
            <div class="basket-items-card">
                <h1>Your Basket</h1>

                <?php 
                $subtotal = 0;
                $totalPostage = 0;

                while ($item = mysqli_fetch_assoc($result)): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;

                    if ($item['postage'] !== 'Free postage' && $item['postage'] !== 'Collection only') {
                        $postageAmount = (float)str_replace('£', '', $item['postage']);
                        $totalPostage += $postageAmount;
                    }
                ?>
                    <div class="basket-item">
                        <img src="images/placeholder-product.jpg" alt="Basket item image" class="basket-item-image">

                        <div class="basket-item-info">
                            <h2><?= $item['title'] ?></h2>
                            <p>Seller: <?= $item['username'] ?></p>
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
                <?php endwhile; ?>

            </div>



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

                <button class="primary-button">Proceed to Checkout</button>
            </aside>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>