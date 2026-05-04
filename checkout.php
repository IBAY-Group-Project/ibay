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
  
    </header>

    <main class="checkout-main">
        <section class="checkout-title">
            <h1>Checkout</h1>
        </section>
        <div class="checkout">
            
            <section class="checkout-details">
                <form action="confirmation.html" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="address">Shipping Address:</label>
                    <textarea id="address" name="address" required></textarea>

                    <fieldset>
                        <legend>Payment Method:</legend>
                        <label for="credit-card">Credit Card</label>
                        <input type="radio" id="credit-card" name="payment" value="credit-card" required>

                        <label for="paypal">PayPal</label>
                        <input type="radio" id="paypal" name="payment" value="paypal">
                    </fieldset>

                    <fieldset> 
                        <legend>Shipping Method:</legend>
                        <label for="standard">Standard Shipping</label>
                        <input type="radio" id="standard" name="shipping" value="standard" required>

                        <label for="express">Express Shipping</label>
                        <input type="radio" id="express" name="shipping" value="express">
                    </fieldset>
                </form>
            </section>

            <section class="checkout-summary">

                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="item-summary">
                        <h4>Product1 x 1</h4>
                        <p>£24.99</p>
                    </div>

                </div>

                <div class="cost-summary">
                    <h4>Subtotal: £24.99</h4>
                    <h4>Shipping: £5.00</h4>
                    <h3>Total: £29.99</h3>

                </div>

                <div class="total-summary">
                    <h3>Total: £29.99</h3>

                </div>

            </section>

        </div>

        <section class="checkout-button">
             <a href="index.html" class="button" id="pay-now">Pay Now</a>
        </section>

    </main>

    <footer>

        <div id="footer", class="footer" >
             &copy; 2026 iBay Marketplace. All rights reserved.
        </div>
    </footer>
    
</body> 

</html>