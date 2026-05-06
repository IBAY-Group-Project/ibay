<?php
include("includes/check.php");
include("connection.php");

$sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
        FROM iBayItems i
        LEFT JOIN iBayImages img ON i.itemId = img.itemId
        WHERE i.sold = 0
        GROUP BY i.itemId
        ORDER BY i.start DESC
        LIMIT 10";
$result = mysqli_query($conn, $sql);
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


        <nav class="category-nav" id="categoryNav">
            <a href="#" class="active" data-category="Technology">Technology</a>
            <a href="#" data-category="Clothing">Clothing</a>
            <a href="#" data-category="Trading Cards">Trading Cards</a>
            <a href="#" data-category="Gardening">Gardening</a>
            <a href="#" data-category="Home">Home</a>
            <a href="#" data-category="Collectables">Collectables</a>
            <a href="#" data-category="Sports">Sports</a>
            <a href="#" data-category="Books">Books</a>
        </nav>
    </header>

    <main class="homepage">
        <section class="featured-section">
            <div class="section-header">
                <h2 id="featuredTitle">Best Selling in Technology</h2>
            </div>

            <div class="featured-carousel">
                <button class="carousel-arrow" id="prevCategory">&#10094;</button>

                <div class="carousel-track" id="featuredProducts">
                    <!-- Filled by JS -->
                </div>

                <button class="carousel-arrow" id="nextCategory">&#10095;</button>
            </div>
        </section>

        <section class="category-grid-section">
            <div class="section-header">
                <h2>Shop by Category</h2>
            </div>

            <div class="category-grid">
                <a href="#" class="category-card">
                    <div class="category-image">💻</div>
                    <h3>Technology</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">👕</div>
                    <h3>Clothing</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">🃏</div>
                    <h3>Trading Cards</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">🪴</div>
                    <h3>Gardening</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">🏠</div>
                    <h3>Home</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">🎁</div>
                    <h3>Collectables</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">⚽</div>
                    <h3>Sports</h3>
                </a>

                <a href="#" class="category-card">
                    <div class="category-image">📚</div>
                    <h3>Books</h3>
                </a>
            </div>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>

</body>
</html>