<?php
include("includes/check.php");
require("includes/db.php");

$where = "WHERE sold = 0";

if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $where .= " AND title LIKE '%$q%'";
}

if (!empty($_GET['category'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['category']);
    $where .= " AND category = '$cat'";
}

if (!empty($_GET['minPrice'])) {
    $min = (float)$_GET['minPrice'];
    $where .= " AND price >= $min";
}

if (!empty($_GET['maxPrice'])) {
    $max = (float)$_GET['maxPrice'];
    $where .= " AND price <= $max";
}

if (!empty($_GET['postage'])) {
    $postage = mysqli_real_escape_string($conn, $_GET['postage']);
    $where .= " AND postage = '$postage'";
}

if (!empty($_GET['postcode'])) {
    $pc = mysqli_real_escape_string($conn, $_GET['postcode']);
    $where .= " AND postcode LIKE '$pc%'";
}

$sql = "SELECT * FROM iBayItems $where";
$count_sql = "SELECT COUNT(*) as total FROM iBayItems $where";
$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_items = $count_row['total'];

$items_per_page = 8;
$total_pages = ceil($total_items / $items_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;
$offset = ($current_page - 1) * $items_per_page;

$sql = "SELECT * FROM iBayItems $where LIMIT $items_per_page OFFSET $offset";

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
        
        </div>
    </header>

    <main class="search-page">
        <section class="search-topbar">
            <div>
                <h1>Search Results</h1>
                <p class="search-subtitle">Browse matching items and refine your search with filters.</p>
            </div>

            <form action="search.php" method="get" class="search-page-form">
                <input type="text" name="q" placeholder="Search for items...">
                <button type="submit" class="primary-button search-page-button">Search</button>
            </form>
        </section>

        <section class="search-layout">
            <aside class="search-filters-card">
                <h2>Advanced Search</h2>

                <form class="advanced-search-form" action="search.php" method="get">
                    <div class="form-group">
                        <label for="searchKeyword">Keyword</label>
                        <input type="text" id="searchKeyword" name="q" placeholder="e.g. headphones">
                    </div>

                    <div class="form-group">
                        <label for="searchCategory">Category</label>
                        <select id="searchCategory" name="category">
                            <option value="">All categories</option>
                            <option value="Technology">Technology</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Trading Cards">Trading Cards</option>
                            <option value="Gardening">Gardening</option>
                            <option value="Home">Home</option>
                            <option value="Collectables">Collectables</option>
                            <option value="Sports">Sports</option>
                            <option value="Books">Books</option>
                        </select>
                    </div>

                    <div class="search-price-row">
                        <div class="form-group">
                            <label for="minPrice">Min Price (£)</label>
                            <input type="number" id="minPrice" name="minPrice" step="0.01" min="0" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="maxPrice">Max Price (£)</label>
                            <input type="number" id="maxPrice" name="maxPrice" step="0.01" min="0" placeholder="100">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="postage">Postage</label>
                        <select id="postage" name="postage">
                            <option value="">Any</option>
                            <option value="Free postage">Free postage</option>
                            <option value="Collection only">Collection only</option>
                            <option value="Paid postage">Paid postage</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="postcodeArea">Postcode Area</label>
                        <input type="text" id="postcodeArea" name="postcode" placeholder="e.g. LE11">
                    </div>

                    <div class="search-filter-buttons">
                        <button type="submit" class="primary-button">Apply Filters</button>
                        <button type="reset" class="secondary-button" id="clearFiltersButton">Clear</button>
                    </div>
                </form>
            </aside>

            <section class="search-results-card">
                <div class="search-results-header">
                <p class="results-count" id="resultsCount">Showing <?= mysqli_num_rows($result) ?> of <?= $total_items ?> results (Page <?= $current_page ?> of <?= $total_pages ?>)</p>

                    <div class="sort-box">
                        <label for="sortBy">Sort by</label>
                        <select id="sortBy" name="sortBy">
                            <option value="low-high">Price: Low to High</option>
                            <option value="high-low">Price: High to Low</option>
                            <option value="title">Title</option>
                        </select>
                    </div>
                </div>

                <div class="search-results-grid">

                <?php while ($item = mysqli_fetch_assoc($result)): ?>

                    <a href="item.php?id=<?= $item['itemId'] ?>" class="search-item-card">

                        <div class="search-item-image">📦</div>

                        <div class="search-item-content">
                            <h3><?= $item['title'] ?></h3>

                            <p>Seller: <?= $item['userId'] ?></p>

                            <p>Postage: <?= $item['postage'] ?></p>

                            <strong>£<?= $item['price'] ?></strong>
                        </div>

                    </a>

                <?php endwhile; ?>

                </div>

                <div class="pagination-bar">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?= $current_page - 1 ?>&q=<?= urlencode($_GET['q'] ?? '') ?>&category=<?= urlencode($_GET['category'] ?? '') ?>&minPrice=<?= urlencode($_GET['minPrice'] ?? '') ?>&maxPrice=<?= urlencode($_GET['maxPrice'] ?? '') ?>" class="secondary-button pagination-button">Previous</a>
                    <?php else: ?>
                        <button class="secondary-button pagination-button" disabled>Previous</button>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $current_page): ?>
                            <button class="pagination-number active-page"><?= $i ?></button>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>&q=<?= urlencode($_GET['q'] ?? '') ?>&category=<?= urlencode($_GET['category'] ?? '') ?>&minPrice=<?= urlencode($_GET['minPrice'] ?? '') ?>&maxPrice=<?= urlencode($_GET['maxPrice'] ?? '') ?>" class="pagination-number"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?= $current_page + 1 ?>&q=<?= urlencode($_GET['q'] ?? '') ?>&category=<?= urlencode($_GET['category'] ?? '') ?>&minPrice=<?= urlencode($_GET['minPrice'] ?? '') ?>&maxPrice=<?= urlencode($_GET['maxPrice'] ?? '') ?>" class="secondary-button pagination-button">Next</a>
                    <?php else: ?>
                        <button class="secondary-button pagination-button" disabled>Next</button>
                    <?php endif; ?>
                </div>

            </section>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>