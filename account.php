<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];

// Fetch user details
$sql = "SELECT * FROM iBayMembers WHERE userId = $userId";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Count active listings
$listingsSql = "SELECT COUNT(*) as count FROM iBayItems WHERE userId = $userId AND sold = 0";
$listingsResult = mysqli_query($conn, $listingsSql);
$listings = mysqli_fetch_assoc($listingsResult)['count'];

//Count basket items
$basketSql = "SELECT COUNT(*) as count FROM iBayBasket WHERE userId = $userId";
$basketResult = mysqli_query($conn , $basketSql);
$basket= mysqli_fetch_assoc($basketResult)['count'];


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
                <div class="account-avatar"><?= strtoupper(substr($_SESSION['firstname'], 0, 1)) ?></div>         <!-- the capital letter of the first letter in the firstname -->
                <h1>My Account</h1>
                <p class="account-name"><?= $user['firstname']?> <?= $user['surname']?> </p>
                <p class="account-userid">@<?= $user['username']?></p>

                <div class="account-profile-meta">
                    <div class="account-meta-row">
                        <span>Email</span>
                        <span><?= $user['email']?></span>
                    </div>
                    <div class="account-meta-row">
                        <span>Rating</span>
                        <span><?= $user['rating']?></span>
                    </div>
                    <div class="account-meta-row">
                        <span>Listings</span>
                        <span><?= $listings?> active</span>
                    </div>
                    <div class="account-meta-row">
                        <span>Basket</span>
                        <span><?= $basket?> items</span>
                    </div>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:24px; height:100%;">
                <div class="account-actions-panel" style="flex:1;">
                    <h2>Quick Actions</h2>

                    <div class="account-actions-grid">
                        <a href="listings.php" class="account-action-card">
                            <h3>My Listings</h3>
                            <p>View and manage your current items for sale.</p>
                        </a>

                        <a href="basket.php" class="account-action-card">
                            <h3>My Basket</h3>
                            <p>See saved items and continue to checkout.</p>
                        </a>

                        <a href="details.php" class="account-action-card">
                            <h3>Account Details</h3>
                            <p>Update profile details, address, and contact info.</p>
                        </a>

                        <a href="orders.php" class="account-action-card">
                            <h3>My Orders</h3>
                            <p>Track current orders and view past purchases.</p>
                        </a>
                    </div>
                </div>

                <a href="logout.php" class="account-action-card" id="logout-card">
                    <h3>Logout</h3>
                    <p>Sign out of your iBay account safely.</p>
                </a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>