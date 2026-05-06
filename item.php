<?php
session_start();
include("php/connection.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$itemId = (int)$_GET['id'];

$itemResult = mysqli_query($conn, "
    SELECT i.*, m.firstname, m.surname, m.userId as sellerId
    FROM iBayItems i
    JOIN iBayMembers m ON i.userId = m.userId
    WHERE i.itemId = '$itemId'
");

if (mysqli_num_rows($itemResult) == 0) {
    header("Location: index.php");
    exit();
}

$item   = mysqli_fetch_assoc($itemResult);
$imagesResult = mysqli_query($conn, "SELECT image FROM iBayImages WHERE itemId = '$itemId'");
$images = [];
while ($img = mysqli_fetch_assoc($imagesResult)) {
    $images[] = $img['image'];
}
if (empty($images)) $images[] = 'placeholder.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - <?php echo htmlspecialchars($item['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.galleryImages = <?php echo json_encode(array_map(function($img) {
            return 'images/products/' . $img;
        }, $images)); ?>;
    </script>
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
                    <a href="account.php">Account</a>
                    <a href="php/logout.php">Logout</a>
                <?php else: ?>
                    <a href="signup.html">Signup</a>
                    <a href="login.html">Login</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search for items...">
                    <button type="submit" class="search-submit-button">Search</button>
                </form>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="basket.php" class="icon-button">&#128722;</a>
                <?php else: ?>
                    <a href="login.html" class="icon-button">&#128100;</a>
                    <a href="basket.php" class="icon-button">&#128722;</a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="category-nav">
            <a href="search.php?category=Technology">Technology</a>
            <a href="search.php?category=Clothing">Clothing</a>
            <a href="search.php?category=Trading Cards">Trading Cards</a>
            <a href="search.php?category=Gardening">Gardening</a>
            <a href="search.php?category=Home">Home</a>
            <a href="search.php?category=Collectables">Collectables</a>
            <a href="search.php?category=Sports">Sports</a>
            <a href="search.php?category=Books">Books</a>
        </nav>
    </header>

    <main class="item-page">
        <section class="item-layout">

            <div class="item-gallery-card">
                <div class="item-gallery-main">
                    <button class="gallery-arrow left" id="prevImage">&#10094;</button>
                    <img id="mainProductImage" class="main-product-image"
                        src="images/products/<?php echo htmlspecialchars($images[0]); ?>"
                        alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <button class="gallery-arrow right" id="nextImage">&#10095;</button>
                </div>
                <div class="item-thumbnails">
                    <?php foreach ($images as $index => $image): ?>
                        <img class="item-thumb <?php echo $index === 0 ? 'active-thumb' : ''; ?>"
                            src="images/products/<?php echo htmlspecialchars($image); ?>"
                            alt="Thumbnail <?php echo $index + 1; ?>"
                            data-index="<?php echo $index; ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="item-info-card">
                <h1 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h1>

                <div class="item-seller-row">
                    <div>
                        <p class="item-seller-name">Seller: <strong><?php echo htmlspecialchars($item['firstname'] . ' ' . $item['surname']); ?></strong></p>
                        <p class="item-seller-rating">Category: <strong><?php echo htmlspecialchars($item['category']); ?></strong></p>
                    </div>
                    <a href="mailto:?subject=iBay enquiry about <?php echo htmlspecialchars($item['title']); ?>" class="contact-seller-link">Contact seller</a>
                </div>

                <div class="item-price-block">
                    <p class="item-price-label">Price</p>
                    <p class="item-price">&pound;<?php echo number_format($item['price'], 2); ?></p>
                </div>

                <div class="item-meta-grid">
                    <div class="item-meta-box">
                        <span class="meta-label">Condition</span>
                        <span class="meta-value"><?php echo htmlspecialchars($item['condition']); ?></span>
                    </div>
                    <div class="item-meta-box">
                        <span class="meta-label">Postage</span>
                        <span class="meta-value"><?php echo htmlspecialchars($item['postage']); ?></span>
                    </div>
                    <div class="item-meta-box">
                        <span class="meta-label">Category</span>
                        <span class="meta-value"><?php echo htmlspecialchars($item['category']); ?></span>
                    </div>
                    <div class="item-meta-box">
                        <span class="meta-label">Listed</span>
                        <span class="meta-value"><?php echo date('d M Y', strtotime($item['start'])); ?></span>
                    </div>
                </div>

                <div class="item-description-block">
                    <h2>Description</h2>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </div>

                <div class="item-actions">
                    <?php if (isset($_SESSION['userId'])): ?>
                        <a href="basket.php?add=<?php echo $item['itemId']; ?>" class="primary-button item-action-button">Add to Basket</a>
                        <a href="checkout.php?id=<?php echo $item['itemId']; ?>" class="secondary-button item-action-button">Buy Now</a>
                    <?php else: ?>
                        <a href="login.html" class="primary-button item-action-button">Log in to Buy</a>
                    <?php endif; ?>
                </div>
            </div>

        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>