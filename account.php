<?php
session_start();
include("php/connection.php");

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['userId'];

$userResult = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE userId = '$userId'");
$user = mysqli_fetch_assoc($userResult);

$listingsResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM iBayItems WHERE userId = '$userId'");
$listingsRow    = mysqli_fetch_assoc($listingsResult);
$listingCount   = $listingsRow['count'];

$avatar = strtoupper(substr($user['firstname'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - My Account</title>
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
                <a href="account.php">Account</a>
                <a href="php/logout.php">Logout</a>
            </nav>
            <div class="header-actions">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search for items...">
                    <button type="submit" class="search-submit-button">Search</button>
                </form>
                <a href="account.php" class="icon-button"><i class="fa-solid fa-user"></i></a>
                <a href="basket.php" class="icon-button"><i class="fa-solid fa-basket-shopping"></i></a>
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

    <main class="account-page">
        <section class="account-layout">

            <div class="account-profile-card">
                <div class="account-avatar"><?php echo $avatar; ?></div>
                <h1>My Account</h1>
                <p class="account-name"><?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['surname']); ?></p>
                <p class="account-userid"><?php echo htmlspecialchars($user['username']); ?></p>
                <div class="account-profile-meta">
                    <div class="account-meta-row">
                        <span><i class="fa-solid fa-envelope" style="margin-right:6px;color:#1f3f8f;"></i>Email</span>
                        <span><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="account-meta-row">
                        <span><i class="fa-solid fa-phone" style="margin-right:6px;color:#1f3f8f;"></i>Phone</span>
                        <span><?php echo $user['phone_number'] ? htmlspecialchars($user['phone_number']) : 'Not set'; ?></span>
                    </div>
                    <div class="account-meta-row">
                        <span><i class="fa-solid fa-star" style="margin-right:6px;color:#1f3f8f;"></i>Rating</span>
                        <span><?php echo $user['rating']; ?></span>
                    </div>
                    <div class="account-meta-row">
                        <span><i class="fa-solid fa-tag" style="margin-right:6px;color:#1f3f8f;"></i>Listings</span>
                        <span><?php echo $listingCount; ?> active</span>
                    </div>
                </div>
            </div>

            <div class="account-actions-panel">
                <h2>Quick Actions</h2>
                <div class="account-actions-grid">

                    <a href="sell.php" class="account-action-card">
                        <i class="fa-solid fa-tag" style="font-size:1.5rem;color:#1f3f8f;margin-bottom:10px;display:block;"></i>
                        <h3>My Listings</h3>
                        <p>View and manage your current items for sale.</p>
                    </a>

                    <a href="basket.php" class="account-action-card">
                        <i class="fa-solid fa-basket-shopping" style="font-size:1.5rem;color:#1f3f8f;margin-bottom:10px;display:block;"></i>
                        <h3>My Basket</h3>
                        <p>See saved items and continue to checkout.</p>
                    </a>

                    <a href="accountdetails.php" class="account-action-card">
                        <i class="fa-solid fa-user-pen" style="font-size:1.5rem;color:#1f3f8f;margin-bottom:10px;display:block;"></i>
                        <h3>Account Details</h3>
                        <p>Update profile details, address, and contact info.</p>
                    </a>

                    <a href="php/logout.php" class="account-action-card">
                        <i class="fa-solid fa-right-from-bracket" style="font-size:1.5rem;color:#1f3f8f;margin-bottom:10px;display:block;"></i>
                        <h3>Logout</h3>
                        <p>Sign out of your iBay account safely.</p>
                    </a>

                </div>

                <div class="account-listings-section">
                    <h2>Your Active Listings</h2>
                    <?php
                    $myItems = mysqli_query($conn, "
                        SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
                        FROM iBayItems i
                        LEFT JOIN iBayImages img ON i.itemId = img.itemId
                        WHERE i.userId = '$userId'
                        GROUP BY i.itemId
                        ORDER BY i.start DESC
                    ");

                    if (mysqli_num_rows($myItems) > 0): ?>
                        <div class="search-results-grid">
                            <?php while ($item = mysqli_fetch_assoc($myItems)): ?>
                                <?php
                                $imageSrc = $item['image'] ? 'images/products/' . htmlspecialchars($item['image']) : 'images/placeholder.jpg';
                                $title    = htmlspecialchars($item['title']);
                                $cat      = htmlspecialchars($item['category']);
                                $cond     = htmlspecialchars($item['condition']);
                                $price    = number_format($item['price'], 2);
                                ?>
                                <div class="product-card">
                                    <img src="<?php echo $imageSrc; ?>" alt="<?php echo $title; ?>">
                                    <div class="product-card-details">
                                        <h3><?php echo $title; ?></h3>
                                        <p class="category"><?php echo $cat; ?></p>
                                        <p class="condition"><?php echo $cond; ?></p>
                                        <p class="price">&pound;<?php echo $price; ?></p>
                                        <a href="item.php?id=<?php echo $item['itemId']; ?>" class="primary-button">View</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-top: 12px;">You have no active listings. <a href="sell.php">List an item now</a></p>
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