<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="top-header">
    <div class="logo">
        <a href="index.php">iBay</a>
    </div>

    <nav class="top-nav">
        <?php if ($is_logged_in = isset($_SESSION['email'])): ?>
            <a href="sell.php">Sell</a>
        <?php else: ?>
            <a href="signup.php">Signup</a>
            <a href="login.php">Login</a>
        <?php endif; ?> 
    </nav>

    <div class="header-actions">
        <form action="search.php" method="get" class="search-form">
            <input type="text" name="q" placeholder="Search for items">
            <button type="submit" class="search-submit-button">Search</button>
        </form>
        <?php if ($is_logged_in = isset($_SESSION['email'])): ?>
            <a href="account.php" class="icon-button">👤</a>
            <a href="basket.php" class="icon-button">🛒</a>
        <?php else: ?>
            <a href="login.php" class="icon-button">👤</a>
            <a href="login.php" class="icon-button">🛒</a>
        <?php endif; ?>   
    </div>
</div>
