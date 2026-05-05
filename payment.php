<?php
include("includes/check.php");
include("includes/db.php");

$subtotal = 0;
$totalPostage = 0;
$paymentMethod = $_POST['paymentMethod'] ?? '';

$buyerId = $_SESSION['userId'];

$basketQuery = "SELECT b.itemId, b.quantity, i.userId as sellerId, i.price, i.postage, i.title 
                FROM iBayBasket b 
                JOIN iBayItems i ON b.itemId = i.itemId 
                WHERE b.userId = ?";
$stmt = $conn->prepare($basketQuery);
$stmt->bind_param("i", $buyerId);
$stmt->execute();
$basketResult = $stmt->get_result();



$basketItems = [];

while ($item = $basketResult->fetch_assoc()) {
    $quantity = (int)$item['quantity'];
    $finalPrice = (float)$item['price'] * $quantity;
    $postageRaw = $item['postage'];

    if (stripos($postageRaw, 'free') !== false) {
        $postageValue = 0;
    } else {
        $postageValue = (float) str_replace(['£', ','], '', $postageRaw);
    }

    $itemPostage = $postageValue * $quantity;
    $subtotal += $finalPrice;
    $totalPostage += $itemPostage;

    $basketItems[] = [
        'itemId'      => (int)$item['itemId'],
        'sellerId'    => (int)$item['sellerId'],
        'quantity'    => $quantity,
        'finalPrice'  => $finalPrice,
        'itemPostage' => $itemPostage,
    ];
}

$orderInsert = "INSERT INTO iBayOrders
(buyerId, orderTotal, totalPostage, paymentMethod, orderDate)
VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($orderInsert);
$stmt->bind_param(
    "idds",
    $buyerId,
    $subtotal,
    $totalPostage,
    $paymentMethod
);
$stmt->execute();
$orderId = $conn->insert_id;

$sellerTotals = [];

// Insert each item into iBaySales and track seller totals
foreach ($basketItems as $item) {
    $itemId      = $item['itemId'];
    $sellerId    = $item['sellerId'];
    $quantity    = $item['quantity'];
    $finalPrice  = $item['finalPrice'];
    $itemPostage = $item['itemPostage'];

    $insertQuery = "INSERT INTO iBaySales (orderId, itemId, buyerId, sellerId, quantity, finalPrice, itemPostage) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "iiiiidd",
        $orderId,
        $itemId,
        $buyerId,
        $sellerId,
        $quantity,
        $finalPrice,
        $itemPostage
    );
    $insertStmt->execute();

    if (!isset($sellerTotals[$sellerId])) {
        $sellerTotals[$sellerId] = 0;
    }
    $sellerTotals[$sellerId] += ($finalPrice * 0.9) + $itemPostage;
}

$total = $subtotal + $totalPostage;




// Deduct from buyer
$buyerQuery = "UPDATE iBayMembers SET iBayBalance = iBayBalance - ? WHERE userId = ?";
$buyerStmt = $conn->prepare($buyerQuery);
$buyerStmt->bind_param("di", $total, $buyerId);
$buyerStmt->execute();

// Add to each seller (90% of sales + postage)
foreach ($sellerTotals as $sellerId => $earnings) {
    $sellerQuery = "UPDATE iBayMembers SET iBayBalance = iBayBalance + ? WHERE userId = ?";
    $sellerStmt = $conn->prepare($sellerQuery);
    $sellerStmt->bind_param("di", $earnings, $sellerId);
    $sellerStmt->execute();
}

//Admin commision  
$adminCommission = $subtotal * 0.1;
$adminQuery = "UPDATE iBayMembers SET iBayBalance = iBayBalance + ? WHERE is_admin = 1";
$adminStmt = $conn->prepare($adminQuery);
$adminStmt->bind_param("d", $adminCommission);
$adminStmt->execute();

// Clear basket
$deleteQuery = "DELETE FROM iBayBasket WHERE userId = ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("i", $buyerId);
$deleteStmt->execute();

header("Location: order_success.php?orderId=" . $orderId);
exit();
?>
