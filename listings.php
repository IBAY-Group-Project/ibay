<?php
include("includes/check.php");
require("includes/db.php");

$userId = $_SESSION['userId'];

$count_sql = "SELECT COUNT(*) as total FROM iBayItems WHERE userId = $userId AND sold = 0";
$count_result = mysqli_query($conn, $count_sql);
$total_items = mysqli_fetch_assoc($count_result)['total'];

$items_per_page = 8;
$total_pages = max(1, ceil($total_items / $items_per_page));
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;
$offset = ($current_page - 1) * $items_per_page;

$sql = "SELECT i.*, img.image FROM iBayItems i LEFT JOIN iBayImages img ON i.itemId = img.itemId WHERE i.userId = $userId AND i.sold = 0 GROUP BY i.itemId LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - My Listings</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

<header class="site-header">
    <?php include("includes/navbar.php"); ?>
</header>

<main class="search-page">
    <section class="search-topbar">
        <div>
            <h1>My Listings</h1>
            <p class="search-subtitle">Manage your active items for sale.</p>
        </div>
        <a href="sell.php" class="primary-button">+ New Listing</a>
    </section>

    <section class="search-results-card" style="width:100%;">
        <div class="search-results-header">
            <p class="results-count">Showing <?= mysqli_num_rows($result) ?> of <?= $total_items ?> listings (Page <?= $current_page ?> of <?= $total_pages ?>)</p>
        </div>

        <div class="search-results-grid">
            <?php while ($item = mysqli_fetch_assoc($result)): ?>
                <div class="search-item-card">
                    <a href="item.php?id=<?= $item['itemId'] ?>" style="text-decoration:none;color:inherit;">
                        <?php
                            $img = $item['image'] ?? '';
                            $src = $img ? (str_starts_with($img, 'http') ? $img : 'images/products/' . htmlspecialchars($img)) : '';
                        ?>
                        <div class="search-item-image">
                            <?php if ($src): ?>
                                <img src="<?= $src ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <?php else: ?>
                                📦
                            <?php endif; ?>
                        </div>
                        <div class="search-item-content">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p>Postage: <?= htmlspecialchars($item['postage']) ?></p>
                            <strong>£<?= $item['price'] ?></strong>
                        </div>
                    </a>
                    <div class="listing-actions">
                        <button class="secondary-button">Edit</button>
                        <button class="secondary-button">Delete</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="pagination-bar">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>" class="secondary-button pagination-button">Previous</a>
            <?php else: ?>
                <button class="secondary-button pagination-button" disabled>Previous</button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <button class="pagination-number active-page"><?= $i ?></button>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="pagination-number"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>" class="secondary-button pagination-button">Next</a>
            <?php else: ?>
                <button class="secondary-button pagination-button" disabled>Next</button>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer class="site-footer">
    <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
</footer>

</body>
</html>
