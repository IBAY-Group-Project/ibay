<?php
session_start();
include("php/connection.php");

$search    = isset($_GET['q'])         ? mysqli_real_escape_string($conn, $_GET['q'])         : '';
$category  = isset($_GET['category'])  ? mysqli_real_escape_string($conn, $_GET['category'])  : '';
$condition = isset($_GET['condition']) ? mysqli_real_escape_string($conn, $_GET['condition'])  : '';
$sort      = isset($_GET['sort'])      ? $_GET['sort']                                         : 'newest';

// Exclude own listings if logged in
$excludeUser = isset($_SESSION['userId']) ? "AND i.userId != '" . $_SESSION['userId'] . "'" : "";

// Build WHERE clause
$where = ["i.sold = 0"];
if ($search)    $where[] = "i.title LIKE '%$search%'";
if ($category)  $where[] = "i.category = '$category'";
if ($condition) $where[] = "i.`condition` = '$condition'";
$whereSQL = 'WHERE ' . implode(' AND ', $where) . ' ' . $excludeUser;

// Build ORDER BY
switch ($sort) {
    case 'price_asc':  $orderBy = 'i.price ASC';  break;
    case 'price_desc': $orderBy = 'i.price DESC'; break;
    case 'oldest':     $orderBy = 'i.start ASC';  break;
    default:           $orderBy = 'i.start DESC';  break;
}

$sql = "SELECT i.itemId, i.title, i.price, i.category, i.`condition`, img.image
        FROM iBayItems i
        LEFT JOIN iBayImages img ON i.itemId = img.itemId
        $whereSQL
        GROUP BY i.itemId
        ORDER BY $orderBy";

$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);

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
                    <a href="account.php">Account</a>
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
                    <a href="basket.php" class="icon-button">&#128722;</a>
                <?php else: ?>
                    <a href="login.html" class="icon-button">&#128100;</a>
                    <a href="basket.php" class="icon-button">&#128722;</a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="category-nav" id="categoryNav">
            <a href="search.php?category=Technology"    <?php if($category=='Technology')    echo 'class="active"'; ?>>Technology</a>
            <a href="search.php?category=Clothing"      <?php if($category=='Clothing')      echo 'class="active"'; ?>>Clothing</a>
            <a href="search.php?category=Trading Cards" <?php if($category=='Trading Cards') echo 'class="active"'; ?>>Trading Cards</a>
            <a href="search.php?category=Gardening"     <?php if($category=='Gardening')     echo 'class="active"'; ?>>Gardening</a>
            <a href="search.php?category=Home"          <?php if($category=='Home')          echo 'class="active"'; ?>>Home</a>
            <a href="search.php?category=Collectables"  <?php if($category=='Collectables')  echo 'class="active"'; ?>>Collectables</a>
            <a href="search.php?category=Sports"        <?php if($category=='Sports')        echo 'class="active"'; ?>>Sports</a>
            <a href="search.php?category=Books"         <?php if($category=='Books')         echo 'class="active"'; ?>>Books</a>
        </nav>
    </header>

    <main class="homepage">
        <section class="featured-section">
            <div class="search-toolbar">
                <div class="search-toolbar-left">
                    <h2><?php echo $pageTitle; ?></h2>
                    <p><?php echo $count; ?> result<?php echo $count != 1 ? 's' : ''; ?> found</p>
                </div>
                <div class="search-toolbar-right">

                    <form method="GET" action="search.php" class="filter-form">
                        <?php if ($search):   ?><input type="hidden" name="q"        value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
                        <?php if ($category): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>"><?php endif; ?>
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                        <select name="condition" onchange="this.form.submit()">
                            <option value="">All Conditions</option>
                            <option value="New"               <?php if($condition=='New')               echo 'selected'; ?>>New</option>
                            <option value="Like New"          <?php if($condition=='Like New')          echo 'selected'; ?>>Like New</option>
                            <option value="Used - Good"       <?php if($condition=='Used - Good')       echo 'selected'; ?>>Used - Good</option>
                            <option value="Used - Acceptable" <?php if($condition=='Used - Acceptable') echo 'selected'; ?>>Used - Acceptable</option>
                        </select>
                    </form>

                    <form method="GET" action="search.php" class="filter-form">
                        <?php if ($search):    ?><input type="hidden" name="q"         value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
                        <?php if ($category):  ?><input type="hidden" name="category"  value="<?php echo htmlspecialchars($category); ?>"><?php endif; ?>
                        <?php if ($condition): ?><input type="hidden" name="condition" value="<?php echo htmlspecialchars($condition); ?>"><?php endif; ?>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="newest"     <?php if($sort=='newest')     echo 'selected'; ?>>Newest First</option>
                            <option value="oldest"     <?php if($sort=='oldest')     echo 'selected'; ?>>Oldest First</option>
                            <option value="price_asc"  <?php if($sort=='price_asc')  echo 'selected'; ?>>Price: Low to High</option>
                            <option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>Price: High to Low</option>
                        </select>
                    </form>

                </div>
            </div>

            <?php if ($count > 0): ?>
                <div class="search-results-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $imageSrc  = $row['image'] ? 'images/products/' . htmlspecialchars($row['image']) : 'images/placeholder.jpg';
                        $title     = htmlspecialchars($row['title']);
                        $cat       = htmlspecialchars($row['category']);
                        $cond      = htmlspecialchars($row['condition']);
                        $price     = number_format($row['price'], 2);
                        ?>
                        <div class="product-card">
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $title; ?>">
                            <div class="product-card-details">
                                <h3><?php echo $title; ?></h3>
                                <p class="category"><?php echo $cat; ?></p>
                                <p class="condition"><?php echo $cond; ?></p>
                                <p class="price">&pound;<?php echo $price; ?></p>
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