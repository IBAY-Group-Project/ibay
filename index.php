<?php 
session_start(); 
include("php/connection.php");
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
                <div class="search-bar">
                    <input type="text" placeholder="Search for items...">
                </div>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="basket.html" class="icon-button">??</a>
                <?php else: ?>
                    <a href="login.html" class="icon-button">??</a>
                    <a href="basket.html" class="icon-button">??</a>
                <?php endif; ?>
            </div>
        </div>
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
                <h2>Latest Listings</h2>
            </div>
            <div class="featured-carousel">
                <button class="carousel-arrow" id="prevCategory">&#10094;</button>
                <div class="carousel-track" id="featuredProducts">
                    <?php
                    $sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
                            FROM iBayItems i
                            LEFT JOIN iBayImages img ON i.itemId = img.itemId
                            GROUP BY i.itemId
                            ORDER BY i.start DESC
                            LIMIT 10";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $imageSrc  = $row['image'] ? 'images/products/' . htmlspecialchars($row['image']) : 'images/placeholder.jpg';
                            $title     = htmlspecialchars($row['title']);
                            $category  = htmlspecialchars($row['category']);
                            $condition = htmlspecialchars($row['condition']);
                            $price     = number_format($row['price'], 2);
                            echo '
                            <div class="product-card">
                                <img src="' . $imageSrc . '" alt="' . $title . '">
                                <div class="product-card-details">
                                    <h3>' . $title . '</h3>
                                    <p class="category">' . $category . '</p>
                                    <p class="condition">' . $condition . '</p>
                                    <p class="price">£' . $price . '</p>
                                    <a href="item.php?id=' . $row['itemId'] . '" class="primary-button">View</a>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>No listings yet. <a href="sell.php">Be the first to sell!</a></p>';
                    }
                    ?>
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
                    <div class="category-image">??</div>
                    <h3>Technology</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">??</div>
                    <h3>Clothing</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">??</div>
                    <h3>Trading Cards</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">??</div>
                    <h3>Gardening</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">??</div>
                    <h3>Home</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">??</div>
                    <h3>Collectables</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">?</div>
                    <h3>Sports</h3>
                </a>
                <a href="#" class="category-card">
                    <div class="category-image">??</div>
                    <h3>Books</h3>
                </a>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>