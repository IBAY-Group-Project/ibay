<?php 
session_start(); 
include("php/connection.php");
$excludeUser = isset($_SESSION['userId']) ? "AND i.userId != '" . $_SESSION['userId'] . "'" : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Home</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                    <a href="basket.php" class="icon-button"><i class="fa-solid fa-basket-shopping"></i></a>
                <?php else: ?>
                    <a href="login.html" class="icon-button"><i class="fa-solid fa-user"></i></a>
                    <a href="basket.php" class="icon-button"><i class="fa-solid fa-basket-shopping"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="category-nav" id="categoryNav">
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
                            WHERE i.sold = 0 $excludeUser
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
                                    <p class="price">&pound;' . $price . '</p>
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

                <a href="search.php?category=Technology" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-laptop"></i>
                    </div>
                    <h3>Technology</h3>
                </a>

                <a href="search.php?category=Clothing" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                    <h3>Clothing</h3>
                </a>

                <a href="search.php?category=Trading Cards" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3>Trading Cards</h3>
                </a>

                <a href="search.php?category=Gardening" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h3>Gardening</h3>
                </a>

                <a href="search.php?category=Home" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <h3>Home</h3>
                </a>

                <a href="search.php?category=Collectables" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h3>Collectables</h3>
                </a>

                <a href="search.php?category=Sports" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <h3>Sports</h3>
                </a>

                <a href="search.php?category=Books" class="category-card">
                    <div class="category-image">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <h3>Books</h3>
                </a>

            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

    <?php include('chatbot.php'); ?>

</body>
</html>