<?php
include("includes/check.php");
include("connection.php");

$buyerId = $_SESSION['userId'];

$userSql = "SELECT firstname, surname, email, phone_number, address, postcode FROM iBayMembers WHERE userId = $buyerId";
$userResult = mysqli_query($conn, $userSql);
$userData = mysqli_fetch_assoc($userResult);

$sql = "SELECT b.quantity, i.price, i.postage
        FROM iBayBasket b
        JOIN iBayItems i ON b.itemId = i.itemId
        WHERE b.userId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $buyerId);
$stmt->execute();
$result = $stmt->get_result();

// Redirect if basket is empty
if ($result->num_rows === 0) {
    header("Location: basket.php");
    exit();
}

$subtotal = 0;
$totalPostage = 0;

while ($row = $result->fetch_assoc()) {
 
    $subtotal += $row['price'] * $row['quantity'];
 
    $postageRaw = $row['postage'];
 
    if (stripos($postageRaw, 'free') !== false || stripos($postageRaw, 'collection') !== false) {
        $postage = 0;
    } else {
        $postage = (float)preg_replace('/[^0-9.]/', '', $postageRaw);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

            <form action="payment.php" method="post" class="checkout-form" id="checkout-form">

                <div class="checkout-form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($userData['firstname'] ?? '') ?>" required>
                </div>

                <div class="checkout-form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($userData['surname'] ?? '') ?>" required>
                </div>

                <div class="checkout-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
                </div>

                <div class="checkout-form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($userData['phone_number'] ?? '') ?>" required>
                </div>

                <div class="checkout-form-group">
                    <label for="address">Shipping Address</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($userData['address'] ?? '') ?>" placeholder="Enter the first line of your address" required>
                </div>

                <div class="checkout-form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" name="postcode" value="<?= htmlspecialchars($userData['postcode'] ?? '') ?>" required>
                </div>

                <fieldset class="checkout-fieldset">
                    <legend>Payment Method</legend>
                    <label>
                        <input type="radio" name="paymentMethod" value="Credit Card" required>
                        Credit Card
                    </label>
                    <label>
                        <input type="radio" name="paymentMethod" value="PayPal">
                        PayPal
                    </label>
                </fieldset>

                <label class="checkout-save-label">
                    <input type="checkbox" name="saveDetails" value="1" checked>
                    Save these details to my account
                </label>

                <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                <input type="hidden" name="totalPostage" value="<?= $totalPostage ?>">

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

            <button type="submit" form="checkout-form" class="primary-button checkout-pay-button">Pay Now</button>
        </aside>

    </section>
</main>

<?php include("includes/footer.php"); ?>


</body>
</html>