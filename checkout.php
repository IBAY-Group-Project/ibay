<?php
include("includes/check.php");
include("connection.php");

$buyerId = $_SESSION['userId'];

$sql = "SELECT b.quantity, i.price, i.postage
        FROM iBayBasket b
        JOIN iBayItems i ON b.itemId = i.itemId
        WHERE b.userId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $buyerId);
$stmt->execute();
$result = $stmt->get_result();

$subtotal = 0;
$totalPostage = 0;

while ($row = $result->fetch_assoc()) {

    $subtotal += $row['price'] * $row['quantity'];

    $postageRaw = $row['postage'];

    if (stripos($postageRaw, 'free') !== false) {
        $postage = 0;
    } else {
        $postage = (float) str_replace(['£'], '', $postageRaw);
    }

    $totalPostage += $postage;
}

$total = $subtotal + $totalPostage;
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
    <?php include("includes/navbar.php"); ?>
</header>

<main class="checkout-page">
    <section class="checkout-layout">

        <div class="checkout-details-card">
            <h1>Checkout</h1>

            <form action="confirmation.html" method="post" class="checkout-form">

                <div class="checkout-form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>

                <div class="checkout-form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>

                <div class="checkout-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                
                <div class="checkout-form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="checkout-form-group">
                    <label for="address">Shipping Address</label>
                    <input type="text" id="address" name="address" placeholder="Enter the first line of your address" required>
                </div>

                
                <div class="checkout-form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" name="postcode" required>
                </div>

                <fieldset class="checkout-fieldset">
                    <legend>Payment Method</legend>

                    <label>
                        <input type="radio" name="payment" value="credit-card" required>
                        Credit Card
                    </label>

                    <label>
                        <input type="radio" name="payment" value="paypal">
                        PayPal
                    </label>
                </fieldset>

                <!--
                <fieldset class="checkout-fieldset">
                    <legend>Shipping Method</legend>

                    <label>
                        <input type="radio" name="shipping" value="standard" required>
                        Standard Shipping
                    </label>

                    <label>
                        <input type="radio" name="shipping" value="express">
                        Express Shipping
                    </label>
                </fieldset>

                -->

            </form>
        </div>

        <aside class="checkout-summary-card">
            <h2>Summary</h2>

            <div class="checkout-summary-row">
                <span>Subtotal</span>
                <span>£<?= number_format((float)$subtotal, 2) ?></span>
            </div>

            <div class="checkout-summary-row">
                <span>Shipping</span>
                <span>£<?= number_format((float)$totalPostage, 2) ?></span>
            </div>

            <div class="checkout-summary-row total-row">
                <span>Total</span>
                <span>£<?= number_format((float)$total, 2) ?></span>
            </div>

            <form action="payment.php" method="post">
                <input type="hidden" name="subtotal" value="<?php echo $subtotal ?? ''; ?>">
                <input type="hidden" name="totalPostage" value="<?php echo $totalPostage ?? ''; ?>">
                <input type="hidden" name="total" value="<?php echo $total ?? ''; ?>">
                <input type="hidden" name="name" value="<?php echo $_POST['name'] ?? ''; ?>">
                <input type="hidden" name="email" value="<?php echo $_POST['email'] ?? ''; ?>">
                <input type="hidden" name="address" value="<?php echo $_POST['address'] ?? ''; ?>">
                <input type="hidden" name="paymentMethod" value="<?php echo $_POST['paymentMethod'] ?? ''; ?>">
                <button type="submit" class="primary-button checkout-pay-button">Pay Now</button>
            </form>
        </aside>

    </section>
</main>

<footer class="site-footer">
    <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
</footer>

</body>
</html>