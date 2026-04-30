<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Online Marketplace</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>

<body> 

    <header class="header"> 

        <div id="menu">
            <img src='images/menu.svg' width='30' alt='Menu Icon' />        
        </div>

        <a class="button" href="sell.html">Sell</a>

        <div id="logo">
            <a href="index.php"> 
                <img src="images/ibay.svg" width="200" alt="iBay Logo" />
            </a>
        </div>

        <div id="login">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Hi, <?php echo $_SESSION['firstName']; ?>!</span>
                <a href="logout.php" class="login-button">Logout</a>
            <?php else: ?>
                <a href="signup.html" class="signup-button">Signup</a>
                <a href="login.html" class="login-button">Login</a>
            <?php endif; ?>
        </div>

    </header>

    <main>

        <search id="search" class="search" aria-label="Site-wide Search"> 
            <input type="text" placeholder="Search for products, brands and more" class="search-bar">
            <a class="button" id="search-button" href="search.html">Search</a>
        </search>  

        <section id="carousel">
            <div class="carousel-content">
                <span class="carousel-arrow left">&lt;</span>
                <span class="carousel-arrow right">&gt;</span>
                Featured Items Here (PLACEHOLDER)
            </div>
            <div class="carousel-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </section>

        <section class="categories">  
            <div class="category" id="Machines">Machines</div> 
            <div class="category" id="Clothing">Clothing</div>
            <div class="category" id="Devices">Devices</div>
            <div class="category" id="Gardening">Gardening</div>
        </section>

    </main>

    <footer>
        <div id="footer" class="footer">
            &copy; 2026 iBay Marketplace. All rights reserved.
        </div>
    </footer>
    
</body> 
</html>