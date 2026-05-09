<?php
include("includes/check.php");
require("includes/db.php");

$excludeUser = isset($_SESSION['userId']) ? "AND i.userId != " . (int)$_SESSION['userId'] : "";

$where = "WHERE i.sold = 0 $excludeUser";

if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $where .= " AND i.title LIKE '%$q%'";
}

if (!empty($_GET['category'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['category']);
    $where .= " AND i.category = '$cat'";
}

if (!empty($_GET['minPrice']) && is_numeric($_GET['minPrice'])) {
    $min = (float)$_GET['minPrice'];
    $where .= " AND i.price >= $min";
}

if (!empty($_GET['maxPrice']) && is_numeric($_GET['maxPrice'])) {
    $max = (float)$_GET['maxPrice'];
    $where .= " AND i.price <= $max";
}

if (!empty($_GET['postage'])) {
    $postage = mysqli_real_escape_string($conn, $_GET['postage']);
    $where .= " AND i.postage = '$postage'";
}

if (!empty($_GET['postcode'])) {
    $pc = mysqli_real_escape_string($conn, $_GET['postcode']);
    $where .= " AND i.postcode LIKE '$pc%'";
}

// Sort
$sort = $_GET['sortBy'] ?? 'newest';
switch ($sort) {
    case 'low-high':  $orderBy = 'i.price ASC';  break;
    case 'high-low':  $orderBy = 'i.price DESC'; break;
    case 'title':     $orderBy = 'i.title ASC';  break;
    default:          $orderBy = 'i.start DESC';  break;
}

$count_sql    = "SELECT COUNT(*) as total FROM iBayItems i $where";
$count_result = mysqli_query($conn, $count_sql);
$count_row    = mysqli_fetch_assoc($count_result);
$total_items  = (int)$count_row['total'];

$items_per_page = 8;
$total_pages    = max(1, ceil($total_items / $items_per_page));
$current_page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;
$offset = ($current_page - 1) * $items_per_page;

$sql    = "SELECT i.*, m.username FROM iBayItems i
           JOIN iBayMembers m ON i.userId = m.userId
           $where
           ORDER BY $orderBy
           LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Build query string for pagination links
$queryParams = http_build_query(array_filter([
    'q'        => $_GET['q']        ?? '',
    'category' => $_GET['category'] ?? '',
    'minPrice' => $_GET['minPrice'] ?? '',
    'maxPrice' => $_GET['maxPrice'] ?? '',
    'postage'  => $_GET['postage']  ?? '',
    'postcode' => $_GET['postcode'] ?? '',
    'sortBy'   => $_GET['sortBy']   ?? '',
]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Search Results</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="js/main.js" defer></script>
</head>
<body>
    <header class="site-header">
        <?php include("includes/navbar.php"); ?>
        </div>
    </header>

    <main class="search-page">
        <section class="search-topbar">
            <div>
                <h1>Search Results</h1>
                <p class="search-subtitle">Browse matching items and refine your search with filters.</p>
            </div>
            <form action="search.php" method="get" class="search-page-form">
                <input type="text" name="q" placeholder="Search for items..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit" class="primary-button search-page-button">Search</button>
            </form>
        </section>

        <section class="search-layout">
            <aside class="search-filters-card">
                <h2>Advanced Search</h2>
                <form class="advanced-search-form" action="search.php" method="get">
                    <div class="form-group">
                        <label for="searchKeyword">Keyword</label>
                        <input type="text" id="searchKeyword" name="q" placeholder="e.g. headphones" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="searchCategory">Category</label>
                        <select id="searchCategory" name="category">
                            <option value="">All categories</option>
                            <?php
                            $cats = ['Technology','Clothing','Trading Cards','Gardening','Home','Collectables','Sports','Books'];
                            foreach ($cats as $c) {
                                $selected = (($_GET['category'] ?? '') === $c) ? 'selected' : '';
                                echo "<option value=\"$c\" $selected>$c</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="search-price-row">
                        <div class="form-group">
                            <label for="minPrice">Min Price (&pound;)</label>
                            <input type="number" id="minPrice" name="minPrice" step="0.01" min="0" placeholder="0" value="<?php echo htmlspecialchars($_GET['minPrice'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="maxPrice">Max Price (&pound;)</label>
                            <input type="number" id="maxPrice" name="maxPrice" step="0.01" min="0" placeholder="1000" value="<?php echo htmlspecialchars($_GET['maxPrice'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="postage">Postage</label>
                        <select id="postage" name="postage">
                            <option value="">Any</option>
                            <option value="Free postage"   <?php echo (($_GET['postage'] ?? '') === 'Free postage')   ? 'selected' : ''; ?>>Free postage</option>
                            <option value="Collection only"<?php echo (($_GET['postage'] ?? '') === 'Collection only') ? 'selected' : ''; ?>>Collection only</option>
                            <option value="&pound;1.99"    <?php echo (($_GET['postage'] ?? '') === '£1.99')           ? 'selected' : ''; ?>>&pound;1.99</option>
                            <option value="&pound;2.99"    <?php echo (($_GET['postage'] ?? '') === '£2.99')           ? 'selected' : ''; ?>>&pound;2.99</option>
                            <option value="&pound;4.99"    <?php echo (($_GET['postage'] ?? '') === '£4.99')           ? 'selected' : ''; ?>>&pound;4.99</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="postcodeArea">Postcode Area</label>
                        <input type="text" id="postcodeArea" name="postcode" placeholder="e.g. LE11" value="<?php echo htmlspecialchars($_GET['postcode'] ?? ''); ?>">
                    </div>

                    <div class="search-filter-buttons">
                        <button type="submit" class="primary-button">Apply Filters</button>
                        <a href="search.php" class="secondary-button">Clear</a>
                    </div>
                </form>
            </aside>

            <section class="search-results-card">
                <div class="search-results-header">
                    <p class="results-count">
                        Showing <?php echo mysqli_num_rows($result); ?> of <?php echo $total_items; ?> results
                        (Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>)
                    </p>
                    <div class="sort-box">
                        <label for="sortBy">Sort by</label>
                        <select id="sortBy" name="sortBy" onchange="this.form.submit()" form="sortForm">
                            <option value="newest"   <?php echo ($sort === 'newest')   ? 'selected' : ''; ?>>Newest</option>
                            <option value="low-high" <?php echo ($sort === 'low-high') ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="high-low" <?php echo ($sort === 'high-low') ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="title"    <?php echo ($sort === 'title')    ? 'selected' : ''; ?>>Title</option>
                        </select>
                        <form id="sortForm" action="search.php" method="get">
                            <?php foreach ($_GET as $key => $val): ?>
                                <?php if ($key !== 'sortBy' && $key !== 'page'): ?>
                                    <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($val); ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>

                <?php if ($total_items === 0): ?>
                    <div style="text-align:center; padding: 60px 20px;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size:3rem;color:#ccc;margin-bottom:16px;display:block;"></i>
                        <h3>No results found</h3>
                        <p style="color:#666;margin-top:8px;">Try different keywords or adjust your filters</p>
                        <a href="search.php" class="primary-button" style="width:auto;display:inline-block;margin-top:20px;padding:12px 24px;">Clear Search</a>
                    </div>
                <?php else: ?>
                    <div class="search-results-grid">
                        <?php while ($item = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $imageSrc = 'images/placeholder.jpg';
                            $imgCheck = mysqli_query($conn, "SELECT image FROM iBayImages WHERE itemId = {$item['itemId']} LIMIT 1");
                            if ($imgCheck && mysqli_num_rows($imgCheck) > 0) {
                                $imgRow   = mysqli_fetch_assoc($imgCheck);
                                $imageSrc = 'images/products/' . htmlspecialchars($imgRow['image']);
                            }
                            ?>
                            <div class="product-card">
                                <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                <div class="product-card-details">
                                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <p class="category"><?php echo htmlspecialchars($item['category']); ?></p>
                                    <p class="condition"><?php echo htmlspecialchars($item['condition']); ?></p>
                                    <p class="price">&pound;<?php echo number_format($item['price'], 2); ?></p>
                                    <a href="item.php?id=<?php echo $item['itemId']; ?>" class="primary-button">View</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="pagination-bar">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>&<?php echo $queryParams; ?>" class="secondary-button pagination-button">Previous</a>
                        <?php else: ?>
                            <button class="secondary-button pagination-button" disabled>Previous</button>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $current_page): ?>
                                <button class="pagination-number active-page"><?php echo $i; ?></button>
                            <?php else: ?>
                                <a href="?page=<?php echo $i; ?>&<?php echo $queryParams; ?>" class="pagination-number"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>&<?php echo $queryParams; ?>" class="secondary-button pagination-button">Next</a>
                        <?php else: ?>
                            <button class="secondary-button pagination-button" disabled>Next</button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>

</body>
</html>