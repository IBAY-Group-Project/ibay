<?php
include("includes/check.php");
include("includes/db.php");

$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($item_id === 0) {
    header("Location: search.php");
    exit;
}

$sql    = "SELECT i.*, m.username, m.firstname, m.surname, m.rating
           FROM iBayItems i
           JOIN iBayMembers m ON i.userId = m.userId
           WHERE i.itemId = $item_id AND i.sold = 0";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    header("Location: search.php");
    exit;
}

$item = mysqli_fetch_assoc($result);

// Get all images for this item
$imgResult = mysqli_query($conn, "SELECT image FROM iBayImages WHERE itemId = $item_id");
$images    = [];
while ($img = mysqli_fetch_assoc($imgResult)) {
    $images[] = $img['image'];
}
if (empty($images)) {
    $images[] = 'placeholder.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - <?php echo htmlspecialchars($item['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        window.galleryImages = <?php echo json_encode(array_map(function($img) {
            return 'images/products/' . $img;
        }, $images)); ?>;
    </script>
    <script src="js/main.js" defer></script>
</head>
<body>
    <header class="site-header">
        <?php include("includes/navbar.php"); ?>
        </div>
    </header>

    <main class="item-page">
        <section class="item-layout">

            <!-- Image gallery -->
            <div class="item-gallery-card">
                <div class="item-gallery-main">
                    <button class="gallery-arrow left" id="prevImage">&#10094;</button>
                    <img id="mainProductImage"
                         class="main-product-image"
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

            <!-- Item details -->
            <div class="item-info-card">
                <h1 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h1>

                <div class="item-seller-row">
                    <div>
                        <p class="item-seller-name">
                            Seller: <strong><?php echo htmlspecialchars($item['username']); ?></strong>
                        </p>
                        <p class="item-seller-rating">
                            Rating: <strong><?php echo $item['rating'] > 0 ? $item['rating'] . ' / 5' : 'No ratings yet'; ?></strong>
                        </p>
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
                        <form action="modify_basket.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="itemId" value="<?php echo $item['itemId']; ?>">
                            <button type="submit" class="primary-button item-action-button">Add to Basket</button>
                        </form>
                        <a href="basket.php" class="secondary-button item-action-button">View Basket</a>
                    <?php else: ?>
                        <a href="login.php" class="primary-button item-action-button">Log in to Buy</a>
                    <?php endif; ?>
                </div>
            </div>

        </section>
    </main>

    <?php include("includes/footer.php"); ?>

</body>
</html>