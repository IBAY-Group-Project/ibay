<?php
$requireLogin = true;
include("includes/check.php");
include("includes/db.php");

// Validate that we came from checkout form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: basket.php");
    exit();
}

$subtotal      = 0;
$totalPostage  = 0;
$paymentMethod = $_POST['paymentMethod'] ?? '';
$phone         = trim($_POST['phone']    ?? '');
$address       = trim($_POST['address']  ?? '');
$postcode      = trim($_POST['postcode'] ?? '');

// Validate required fields
if (empty($address) || empty($postcode) || empty($paymentMethod)) {
    header("Location: checkout.php");
    exit();
}

$buyerId = $_SESSION['userId'];

// Get basket items first - validate basket is not empty
$basketQuery = "SELECT b.itemId, b.quantity, i.userId as sellerId, i.price, i.postage, i.title, i.sold
                FROM iBayBasket b
                JOIN iBayItems i ON b.itemId = i.itemId
                WHERE b.userId = ?";
$stmt = $conn->prepare($basketQuery);
$stmt->bind_param("i", $buyerId);
$stmt->execute();
$basketResult = $stmt->get_result();

if ($basketResult->num_rows === 0) {
    header("Location: basket.php");
    exit();
}

// Save details to account if requested
if (!empty($_POST['saveDetails'])) {
    $phoneSafe    = mysqli_real_escape_string($conn, $phone);
    $addressSafe  = mysqli_real_escape_string($conn, $address);
    $postcodeSafe = mysqli_real_escape_string($conn, $postcode);
    $firstnameSafe = mysqli_real_escape_string($conn, trim($_POST['firstName'] ?? ''));
    $surnameSafe   = mysqli_real_escape_string($conn, trim($_POST['lastName']  ?? ''));
    mysqli_query($conn, "UPDATE iBayMembers SET phone_number='$phoneSafe',  address='$addressSafe', postcode='$postcodeSafe', firstname='$firstnameSafe', surname='$surnameSafe' WHERE userId=$buyerId");
}

// Calculate delivery date (5 working days)
$deliveryDate = new DateTime();
$daysAdded    = 0;
while ($daysAdded < 5) {
    $deliveryDate->modify('+1 day');
    if ($deliveryDate->format('N') < 6) {
        $daysAdded++;
    }
}
$deliveryDateStr = $deliveryDate->format('Y-m-d');

$basketItems = [];

while ($item = $basketResult->fetch_assoc()) {
    // Skip already sold items
    if ($item['sold'] == 1) continue;

    $quantity     = (int)$item['quantity'];
    $finalPrice   = (float)$item['price'] * $quantity;
    $postageRaw   = $item['postage'];

    if (stripos($postageRaw, 'free') !== false || stripos($postageRaw, 'collection') !== false) {
        $postageValue = 0;
    } else {
        $postageValue = (float)preg_replace('/[^0-9.]/', '', $postageRaw);
    }

    $itemPostage   = $postageValue * $quantity;
    $subtotal     += $finalPrice;
    $totalPostage += $itemPostage;

    $basketItems[] = [
        'itemId'      => (int)$item['itemId'],
        'sellerId'    => (int)$item['sellerId'],
        'quantity'    => $quantity,
        'finalPrice'  => $finalPrice,
        'itemPostage' => $itemPostage,
    ];
}

if (empty($basketItems)) {
    header("Location: basket.php");
    exit();
}

// Insert into iBayOrders
$orderInsert = "INSERT INTO iBayOrders
    (buyerId, orderTotal, totalPostage, paymentMethod, orderDate, shippingAddress, postcode, phone, deliveryDate)
    VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
$stmt = $conn->prepare($orderInsert);
$stmt->bind_param("iddsssss", $buyerId, $subtotal, $totalPostage, $paymentMethod, $address, $postcode, $phone, $deliveryDateStr);
$stmt->execute();
$orderId = $conn->insert_id;

$sellerTotals = [];

// Insert each item into iBaySales and mark as sold
foreach ($basketItems as $item) {
    $itemId      = $item['itemId'];
    $sellerId    = $item['sellerId'];
    $quantity    = $item['quantity'];
    $finalPrice  = $item['finalPrice'];
    $itemPostage = $item['itemPostage'];

    $insertStmt = $conn->prepare("INSERT INTO iBaySales (orderId, itemId, buyerId, sellerId, quantity, finalPrice, itemPostage) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("iiiiidd", $orderId, $itemId, $buyerId, $sellerId, $quantity, $finalPrice, $itemPostage);
    $insertStmt->execute();

    // Mark item as sold so it disappears from listings
    $conn->query("UPDATE iBayItems SET sold = 1 WHERE itemId = $itemId");

    if (!isset($sellerTotals[$sellerId])) {
        $sellerTotals[$sellerId] = 0;
    }
    $sellerTotals[$sellerId] += ($finalPrice * 0.9) + $itemPostage;
}

$total = $subtotal + $totalPostage;

// Deduct from buyer balance
$buyerStmt = $conn->prepare("UPDATE iBayMembers SET iBayBalance = iBayBalance - ? WHERE userId = ?");
$buyerStmt->bind_param("di", $total, $buyerId);
$buyerStmt->execute();

// Add to each seller balance (90% of sales + postage)
foreach ($sellerTotals as $sellerId => $earnings) {
    $sellerStmt = $conn->prepare("UPDATE iBayMembers SET iBayBalance = iBayBalance + ? WHERE userId = ?");
    $sellerStmt->bind_param("di", $earnings, $sellerId);
    $sellerStmt->execute();
}

// Admin commission (10% of subtotal)
$adminCommission = $subtotal * 0.1;
$adminStmt       = $conn->prepare("UPDATE iBayMembers SET iBayBalance = iBayBalance + ? WHERE is_admin = 1");
$adminStmt->bind_param("d", $adminCommission);
$adminStmt->execute();

// Clear basket
$deleteStmt = $conn->prepare("DELETE FROM iBayBasket WHERE userId = ?");
$deleteStmt->bind_param("i", $buyerId);
$deleteStmt->execute();

header("Location: order_success.php?orderId=" . $orderId);
exit();
?>