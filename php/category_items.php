<?php
session_start();
header('Content-Type: application/json');
include('../connection.php');

$category = isset($_GET['category']) ? trim($_GET['category']) : '';
if (!$category) {
    echo json_encode([]);
    exit;
}

$excludeUser = isset($_SESSION['userId']) ? "AND i.userId != " . (int)$_SESSION['userId'] : "";

$stmt = mysqli_prepare($conn,
    "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
     FROM iBayItems i
     LEFT JOIN iBayImages img ON i.itemId = img.itemId
     WHERE i.sold = 0 AND i.category = ? $excludeUser
     GROUP BY i.itemId
     ORDER BY i.start DESC
     LIMIT 4"
);
mysqli_stmt_bind_param($stmt, "s", $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = [
        'itemId'    => $row['itemId'],
        'title'     => $row['title'],
        'price'     => number_format($row['price'], 2),
        'category'  => $row['category'],
        'condition' => $row['condition'],
        'image'     => $row['image'] ? (str_starts_with($row['image'], 'http') ? $row['image'] : 'images/products/' . $row['image']) : 'images/placeholder.jpg',
    ];
}

echo json_encode($items);
