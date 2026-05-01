<?php
session_start();
include("php/connection.php");
 
// Get search query and category from URL
$search   = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
 
// Build query based on what was passed
if ($search && $category) {
    $sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
            FROM iBayItems i
            LEFT JOIN iBayImages img ON i.itemId = img.itemId
            WHERE i.title LIKE '%$search%' AND i.category = '$category'
            GROUP BY i.itemId
            ORDER BY i.start DESC";
} else if ($search) {
    $sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
            FROM iBayItems i
            LEFT JOIN iBayImages img ON i.itemId = img.itemId
            WHERE i.title LIKE '%$search%'
            GROUP BY i.itemId
            ORDER BY i.start DESC";
} else if ($category) {
    $sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
            FROM iBayItems i
            LEFT JOIN iBayImages img ON i.itemId = img.itemId
            WHERE i.category = '$category'
            GROUP BY i.itemId
            ORDER BY i.start DESC";
} else {
    $sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
            FROM iBayItems i
            LEFT JOIN iBayImages img ON i.itemId = img.itemId
            GROUP BY i.itemId
            ORDER BY i.start DESC";
}
 
$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);
 
// Build page title
if ($search) {
    $pageTitle = 'Search results for "' . htmlspecialchars($search) . '"';
} else if ($category) {
    $pageTitle = htmlspecialchars($category);
} else {
    $pageTitle = 'All Listings';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - <?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="top-header">
            <div class="logo">
                <a href="index.php">iBay</a>
            </div>
            <nav class="top-nav">
                <a href="sell.php">Sell</a>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="account.html">Account</a>
                    <a href="php/logout.php">Logout</a>
                <?php else: ?>
                    <a href="signup.html">Signup</a>
                    <a href="login.html">Login</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <form action="search.php" method="GET" style="display: flex; align-items: center; gap: 8px;">
                    <div class="search-bar">
                        <input type="text" name="q" placeholder="Search for items..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <button type="submit" class="primary-button" style="width: auto; padding: 12px 20px;">Search</button>
                </form>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="basket.html" class="icon-button">??</a>
                <?php else: ?>
                    <a href="login.html" class="icon-button">??</a>
                    <a href="basket.html" class="icon-button">??</a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="category-nav" id="categoryNav">
            <a href="search.php?category=Technology" <?php if($category=='Technology') echo 'class="active"'; ?>>Technology</a>
            <a href="search.php?category=Clothing" <?php if($category=='Clothing') echo 'class="active"'; ?>>Clothing</a>
            <a href="search.php?category=Trading Cards" <?php if($category=='Trading Cards') echo 'class="active"'; ?>>Trading Cards</a>
            <a href="search.php?category=Gardening" <?php if($category=='Gardening') echo 'class="active"'; ?>>Gardening</a>
            <a href="search.php?category=Home" <?php if($category=='Home') echo 'class="active"'; ?>>Home</a>
            <a href="search.php?category=Collectables" <?php if($category=='Collectables') echo 'class="active"'; ?>>Collectables</a>
            <a href="search.php?category=Sports" <?php if($category=='Sports') echo 'class="active"'; ?>>Sports</a>
            <a href="search.php?category=Books" <?php if($category=='Books') echo 'class="active"'; ?>>Books</a>
        </nav>
    </header>
 
    <main class="homepage">
        <section class="featured-section">
            <div class="section-header">
                <h2><?php echo $pageTitle; ?></h2>
                <p style="color: #666; margin-top: 6px;"><?php echo $count; ?> result<?php echo $count != 1 ? 's' : ''; ?> found</p>
            </div>
 
            <?php if ($count > 0): ?>
                <div class="search-results-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $imageSrc  = $row['image'] ? 'images/products/' . htmlspecialchars($row['image']) : 'images/placeholder.jpg';
                        $title     = htmlspecialchars($row['title']);
                        $category  = htmlspecialchars($row['category']);
                        $condition = htmlspecialchars($row['condition']);
                        $price     = number_format($row['price'], 2);
                        ?>
                        <div class="product-card">
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $title; ?>">
                            <div class="product-card-details">
                                <h3><?php echo $title; ?></h3>
                                <p class="category"><?php echo $category; ?></p>
                                <p class="condition"><?php echo $condition; ?></p>
                                <p class="price">£<?php echo $price; ?></p>
                                <a href="item.php?id=<?php echo $row['itemId']; ?>" class="primary-button">View</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <h3>No results found</h3>
                    <p style="color: #666; margin-top: 8px;">Try a different search term or browse by category</p>
                    <a href="index.php" class="primary-button" style="width: auto; display: inline-block; margin-top: 20px; padding: 12px 24px;">Back to Home</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
 
    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>