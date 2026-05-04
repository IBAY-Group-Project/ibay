<?php
include("includes/check.php");
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

                <div class="basket-item">
                    <img src="images/placeholder-product.jpg" alt="Basket item image" class="basket-item-image">

                    <div class="basket-item-info">
                        <h2>Item Title Placeholder</h2>
                        <p>Seller: seller123</p>
                        <p>Postage: Free postage</p>
                        <p class="basket-item-price">£83.90</p>
                    </div>

                    <button class="secondary-button basket-remove-button">Remove</button>
                </div>
            </div>

            <aside class="basket-summary-card">
                <h2>Summary</h2>

                <div class="basket-summary-row">
                    <span>Subtotal</span>
                    <span>£83.90</span>
                </div>

                <div class="basket-summary-row">
                    <span>Postage</span>
                    <span>£0.00</span>
                </div>

                <div class="basket-summary-row total-row">
                    <span>Total</span>
                    <span>£83.90</span>
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