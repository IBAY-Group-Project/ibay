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
                <form action="search.php" method="GET" style="display: flex; align-items: center; gap: 8px;">
                    <div class="search-bar">
                        <input type="text" name="q" placeholder="Search for items...">
                    </div>
                    <button type="submit" class="primary-button" style="width: auto; padding: 12px 20px;">Search</button>
                </form>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="basket.html" class="icon-button">??</a>
                <?php else: ?>
                    <a href="login.html" class="icon-button">??</a>
                    <a href="basket.html" class="icon-button">??</a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="category-nav" id="categoryNav">
            <a href="search.php?category=Technology" data-category="Technology">Technology</a>
            <a href="search.php?category=Clothing" data-category="Clothing">Clothing</a>
            <a href="search.php?category=Trading Cards" data-category="Trading Cards">Trading Cards</a>
            <a href="search.php?category=Gardening" data-category="Gardening">Gardening</a>
            <a href="search.php?category=Home" data-category="Home">Home</a>
            <a href="search.php?category=Collectables" data-category="Collectables">Collectables</a>
            <a href="search.php?category=Sports" data-category="Sports">Sports</a>
            <a href="search.php?category=Books" data-category="Books">Books</a>
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
                <a href="search.php?category=Technology" class="category-card">
                    <div class="category-image">&#128187;</div>
                    <h3>Technology</h3>
                </a>
                <a href="search.php?category=Clothing" class="category-card">
                    <div class="category-image">&#128085;</div>
                    <h3>Clothing</h3>
                </a>
                <a href="search.php?category=Trading Cards" class="category-card">
                    <div class="category-image">&#127183;</div>
                    <h3>Trading Cards</h3>
                </a>
                <a href="search.php?category=Gardening" class="category-card">
                    <div class="category-image">&#127807;</div>
                    <h3>Gardening</h3>
                </a>
                <a href="search.php?category=Home" class="category-card">
                    <div class="category-image">&#127968;</div>
                    <h3>Home</h3>
                </a>
                <a href="search.php?category=Collectables" class="category-card">
                    <div class="category-image">&#127942;</div>
                    <h3>Collectables</h3>
                </a>
                <a href="search.php?category=Sports" class="category-card">
                    <div class="category-image">&#9917;</div>
                    <h3>Sports</h3>
                </a>
                <a href="search.php?category=Books" class="category-card">
                    <div class="category-image">&#128218;</div>
                    <h3>Books</h3>
                </a>
            </div>
        </section>
    </main>
 
    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
 
    <!-- -- Chatbot -- -->
    <div id="chat-bubble" onclick="toggleChat()">&#128172;</div>
 
    <div id="chat-window">
        <div id="chat-header">
            <span>iBay Help</span>
            <button onclick="toggleChat()" id="chat-close">?</button>
        </div>
        <div id="chat-messages">
            <div class="chat-msg bot">Hi! How can I help you today? Choose a question below.</div>
        </div>
        <div id="chat-questions">
            <button class="chat-q" onclick="askQuestion(this)" data-answer="To create an account, click the 'Signup' button in the top navigation bar. Fill in your first name, surname, email address, and a strong password. Once submitted you will be redirected to the login page.">How do I create an account?</button>
            <button class="chat-q" onclick="askQuestion(this)" data-answer="To list an item, click the 'Sell' button in the top navigation bar. You must be logged in to sell. Fill in the title, category, condition, price, postage and description, then upload up to 3 images and click Publish Listing.">How do I list an item for sale?</button>
            <button class="chat-q" onclick="askQuestion(this)" data-answer="Use the search bar at the top of the page and type what you are looking for, then click Search. You can also browse by clicking a category in the navigation bar or the Shop by Category section.">How do I search for items?</button>
            <button class="chat-q" onclick="askQuestion(this)" data-answer="To add an item to your basket you must be logged in. Navigate to the item page by clicking View on any listing, then click the Add to Basket button. You can view your basket by clicking the basket icon in the top right.">How do I add an item to my basket?</button>
            <button class="chat-q" onclick="askQuestion(this)" data-answer="To contact a seller, navigate to the item page by clicking View on any listing. The seller's username is displayed on the item page and you can send them a message from there.">How do I contact a seller?</button>
            <button class="chat-q" onclick="askQuestion(this)" data-answer="To return an item, go to your Account page and find the order in your purchase history. Click on the order and select Request Return. The seller will be notified and will arrange the return with you.">How do I return an item?</button>
            <button class="chat-q" onclick="askQuestion(this)" data-answer="To change your password, click Account in the top navigation bar and go to Account Information. From there you can update your password. You will need to enter your current password to confirm the change.">How do I change my password?</button>
        </div>
        <div id="chat-back" style="display:none;">
            <button class="secondary-button" onclick="resetChat()" style="width:100%; margin-top: 8px;">&#8592; Back to questions</button>
        </div>
    </div>
 
    <style>
        #chat-bubble {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 58px;
            height: 58px;
            background-color: #1f3f8f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: transform 0.2s ease;
        }
 
        #chat-bubble:hover {
            transform: scale(1.08);
        }
 
        #chat-window {
            position: fixed;
            bottom: 100px;
            right: 28px;
            width: 340px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }
 
        #chat-window.open {
            display: flex;
        }
 
        #chat-header {
            background-color: #1f3f8f;
            color: white;
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 1rem;
        }
 
        #chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }
 
        #chat-messages {
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 160px;
            overflow-y: auto;
        }
 
        .chat-msg {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            line-height: 1.4;
            max-width: 90%;
        }
 
        .chat-msg.bot {
            background-color: #f0f4ff;
            color: #1f1f1f;
            align-self: flex-start;
        }
 
        .chat-msg.user {
            background-color: #1f3f8f;
            color: white;
            align-self: flex-end;
        }
 
        #chat-questions {
            padding: 10px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-top: 1px solid #eee;
            max-height: 220px;
            overflow-y: auto;
        }
 
        .chat-q {
            background-color: #f5f7ff;
            border: 1px solid #d0d9f5;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.88rem;
            text-align: left;
            cursor: pointer;
            color: #1f3f8f;
            font-weight: 500;
            transition: background-color 0.15s ease;
        }
 
        .chat-q:hover {
            background-color: #e0e8ff;
        }
 
        #chat-back {
            padding: 0 14px 14px;
        }
    </style>
 
    <script>
        function toggleChat() {
            const win = document.getElementById('chat-window');
            win.classList.toggle('open');
        }
 
        function askQuestion(btn) {
            const question = btn.textContent;
            const answer   = btn.getAttribute('data-answer');
 
            const messages = document.getElementById('chat-messages');
            const questions = document.getElementById('chat-questions');
            const backDiv  = document.getElementById('chat-back');
 
            // Add user message
            const userMsg = document.createElement('div');
            userMsg.classList.add('chat-msg', 'user');
            userMsg.textContent = question;
            messages.appendChild(userMsg);
 
            // Add bot answer
            const botMsg = document.createElement('div');
            botMsg.classList.add('chat-msg', 'bot');
            botMsg.textContent = answer;
            messages.appendChild(botMsg);
 
            // Scroll to bottom
            messages.scrollTop = messages.scrollHeight;
 
            // Hide questions, show back button
            questions.style.display = 'none';
            backDiv.style.display   = 'block';
        }
 
        function resetChat() {
            const messages  = document.getElementById('chat-messages');
            const questions = document.getElementById('chat-questions');
            const backDiv   = document.getElementById('chat-back');
 
            // Clear messages back to just the greeting
            messages.innerHTML = '<div class="chat-msg bot">Hi! How can I help you today? Choose a question below.</div>';
 
            // Show questions again
            questions.style.display = 'flex';
            backDiv.style.display   = 'none';
        }
    </script>
 
</body>
</html>