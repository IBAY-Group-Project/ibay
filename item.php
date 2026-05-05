<?php
include("includes/check.php");
require("includes/db.php");

// Get item ID from URL
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($item_id === 0) {
    header("Location: search.php");
    exit;
}

// Fetch item from database

$sql = "SELECT i.*, m.username FROM iBayItems i 
        JOIN iBayMembers m ON i.userId = m.userId 
        WHERE itemId = $item_id AND sold = 0";
        
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    header("Location: search.php");
    exit;
}

$item = mysqli_fetch_assoc($result);
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

    <main class="item-page">
        <section class="item-layout">
            <div class="item-gallery-card">
                <div class="item-gallery-main">
                    <button class="gallery-arrow left" id="prevImage">&#10094;</button>

                    <img
                        id="mainProductImage"
                        class="main-product-image"
                        src="images/placeholder-product.jpg"
                        alt="Product image">

                    <button class="gallery-arrow right" id="nextImage">&#10095;</button>
                </div>

                <div class="item-thumbnails">
                    <img
                        class="item-thumb active-thumb"
                        src="images/placeholder-product.jpg"
                        alt="Thumbnail 1"
                        data-index="0">

                    <img
                        class="item-thumb"
                        src="images/placeholder-product-2.jpg"
                        alt="Thumbnail 2"
                        data-index="1">
                </div>
            </div>

            <div class="item-info-card">
                <h1 class="item-title" id="itemTitle"><?= $item['title'] ?></h1>

                <div class="item-seller-row">
                    <div>
                        <p class="item-seller-name">
                            Seller: <span id="sellerUserId"><?= $item['username'] ?></span>
                        </p>
                        <p class="item-seller-rating">
                            Rating: <span id="sellerRating">98</span>
                        </p>
                    </div>

                    <a href="#" class="contact-seller-link">Contact seller</a>
                </div>

                <div class="item-price-block">
                    <p class="item-price-label">Price</p>
                    <p class="item-price" id="itemPrice">£<?= $item['price'] ?></p>
                </div>

                <div class="item-meta-grid">
                    <div class="item-meta-box">
                        <span class="meta-label">Postage</span>
                        <span class="meta-value" id="itemPostage"><?= $item['postage'] ?></span>
                    </div>

                    <div class="item-meta-box">
                        <span class="meta-label">Listed</span>
                        <span class="meta-value" id="itemStart"><?= date('d M Y', strtotime($item['start'])) ?></span>
                    </div>

                    <div class="item-meta-box">
                        <span class="meta-label">Condition</span>
                        <span class="meta-value" id=itemCondition><?= $item['condition']?></span>
                    </div>

                    <!-- Im hiding this for now--> 
                    
                    <!--
                    <div class="item-meta-box">
                        <span class="meta-label">Ends</span>
                        <span class="meta-value" id="itemFinish">08 May 2026</span>
                    </div>

                    <div class="item-meta-box">
                        <span class="meta-label">Time left</span>
                        <span class="meta-value" id="itemTimeLeft">2 days left</span>
                    </div>
                    --> 
                </div>

                <div class="item-description-block">
                    <h2>Description</h2>
                    <p id="itemDescription">
                        <?= $item['description']?>
                    </p>
                </div>

            <form action="modify_basket.php" method="post" class="item-actions">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="itemId" value="<?= $item['itemId'] ?>">
                <button type="submit" class="primary-button item-action-button">Add to Basket</button>
                <button type="button" class="secondary-button item-action-button">Buy Now</button>
            </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>