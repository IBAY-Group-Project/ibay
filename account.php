<?php
session_start();
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

$is_logged_in = isset($_SESSION['email']);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - My Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="site-header">
        <?php include("includes/navbar.php"); ?>   
    </header>

    <main class="account-page">
        <section class="account-layout">
            <div class="account-profile-card">
                <div class="account-avatar">A</div>
                <h1>My Account</h1>
                <p class="account-name">Adam User</p>
                <p class="account-userid">@adam123</p>

                <div class="account-profile-meta">
                    <div class="account-meta-row">
                        <span>Email</span>
                        <span>adam@email.com</span>
                    </div>
                    <div class="account-meta-row">
                        <span>Rating</span>
                        <span>98</span>
                    </div>
                    <div class="account-meta-row">
                        <span>Listings</span>
                        <span>4 active</span>
                    </div>
                    <div class="account-meta-row">
                        <span>Basket</span>
                        <span>2 items</span>
                    </div>
                </div>
            </div>

            <div class="account-actions-panel">
                <h2>Quick Actions</h2>

                <div class="account-actions-grid">
                    <a href="sell.php" class="account-action-card">
                        <h3>My Listings</h3>
                        <p>View and manage your current items for sale.</p>
                    </a>

                    <a href="basket.php" class="account-action-card">
                        <h3>My Basket</h3>
                        <p>See saved items and continue to checkout.</p>
                    </a>

                    <a href="#" class="account-action-card">
                        <h3>Account Details</h3>
                        <p>Update profile details, address, and contact info.</p>
                    </a>

                    <a href="logout.php" class="account-action-card">
                        <h3>Logout</h3>
                        <p>Sign out of your iBay account safely.</p>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>