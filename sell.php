<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];

// Handle form submission

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    $condition = $_POST['condition'] ?? '';
    $price = $_POST['price'] ?? '0';
    $postage = $_POST['postage'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (!empty($title) && !empty($category)) {
        $sql = "INSERT INTO iBayItems (userId, title, category, `condition`, price, postage, description, sold) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssdss", $userId, $title, $category, $condition, $price, $postage, $description);
        
        if (mysqli_stmt_execute($stmt)) {
            $itemId = mysqli_insert_id($conn);

            foreach (['image1', 'image2'] as $field) {
                if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) continue;

                $file     = $_FILES[$field];
                $filename = uniqid() . '_' . basename($file['name']);
                $dest     = __DIR__ . '/images/products/' . $filename;

                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $mime = $file['type'];
                    $size = $file['size'];
                    $imgStmt = mysqli_prepare($conn, "INSERT INTO iBayImages (image, mimeType, imageSize, itemId) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($imgStmt, "ssii", $filename, $mime, $size, $itemId);
                    mysqli_stmt_execute($imgStmt);
                }
            }

            header("Location: account.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}



// Fetch user details
$sql = "SELECT * FROM iBayMembers WHERE userId = $userId";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Count active listings
$listingsSql = "SELECT COUNT(*) as count FROM iBayItems WHERE userId = $userId AND sold = 0";
$listingsResult = mysqli_query($conn, $listingsSql);
$listings = mysqli_fetch_assoc($listingsResult)['count'];

$basketSql = "SELECT COUNT(*) as count FROM iBayItems WHERE userId = $userId";
$basketResult = mysqli_query($conn, $basketSql);
$basket = mysqli_fetch_assoc($basketResult)['count'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Sell</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

    <header class="site-header">
        <?php include("includes/navbar.php"); ?>


        <nav class="seller-subnav">
            <a href="#" class="active">Seller dashboard</a>
            <a href="#">Drafts</a>
            <a href="#">Preview</a>
            <a href="#">Publish</a>
        </nav>
    </header>

    <main class="seller-page">
        <section class="seller-card">
            <div class="seller-header">
                <div>
                    <p class="seller-eyebrow">Seller upload page</p>
                    <h1>Create or Edit a Listing</h1>
                    <p class="seller-subtitle">
                        Complete the listing details below. Keep it clear, compact, and easy to review.
                        <button class="secondary-button" onclick="document.getElementById('how-to-sell-modal').style.display='flex'" style="margin-left:12px;padding:6px 14px;font-size:0.85rem;">? How to sell</button>
                    </p>
                </div>

                <div class="seller-status">
                    <span class="status-pill active">Draft Ready</span>
                </div>
            </div>

            <form class="seller-form" action="sell.php" method="post" enctype="multipart/form-data">
                <div class="seller-left">
                    <div class="seller-grid">
                        <div class="form-group seller-wide">
                            <label for="title">Item title</label>
                            <input type="text" id="title" name="title" placeholder="e.g. Sony WH-1000XM4 Headphones" required>
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option>Technology</option>
                                <option>Clothing</option>
                                <option>Trading Cards</option>
                                <option>Gardening</option>
                                <option>Home</option>
                                <option>Collectables</option>
                                <option>Sports</option>
                                <option>Books</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="condition">Condition</label>
                            <select id="condition" name="condition">
                                <option>New</option>
                                <option>Like New</option>
                                <option>Used - Good</option>
                                <option>Used - Acceptable</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">Starting price (£)</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="postage">Postage</label>
                            <select id="postage" name="postage" required>
                                <option>Free postage</option>
                                <option>£1.99</option>
                                <option>£2.99</option>
                                <option>£4.99</option>
                                <option>Collection only</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="finish">Auction end</label>
                            <input type="date" id="finish" name="finish" required>
                        </div>

                        <div class="form-group">
                            <label for="postcode">Postcode area</label>
                            <input type="text" id="postcode" name="postcode" placeholder="e.g. LE11" required>
                        </div>

                        <div class="form-group seller-wide">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="6" placeholder="Describe condition, included accessories, delivery details, and any flaws." required></textarea>
                            <div class="description-meta">
                                <span>Write clearly so buyers trust the listing.</span>
                                <span id="charCount">0 / 500</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="seller-right">
                    <div class="seller-panel">
                        <h2>Upload 2 images</h2>
                        <p>Upload two clear images of the item as required by the brief.</p>

                        <div class="upload-grid">
                            <label class="upload-slot" for="image1" id="slot1">
                                <input type="file" id="image1" name="image1" accept="image/*" hidden>
                                <img id="preview1" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                <span class="upload-plus" id="plus1">+</span>
                                <span id="label1">Main image</span>
                            </label>

                            <label class="upload-slot" for="image2" id="slot2">
                                <input type="file" id="image2" name="image2" accept="image/*" hidden>
                                <img id="preview2" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                <span class="upload-plus" id="plus2">+</span>
                                <span id="label2">Second image</span>
                            </label>
                        </div>
                    </div>

                    <div class="seller-panel">
                        <h2>Live listing preview</h2>
                        <div class="listing-preview-card">
                            <div class="listing-preview-image" id="previewImageBox" style="overflow:hidden;display:flex;align-items:center;justify-content:center;">
                                <img id="previewMainImage" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
                                <span id="previewPlaceholder">Preview</span>
                            </div>
                            <div class="listing-preview-content">
                                <h3 id="previewTitle">Your item title</h3>
                                <p id="previewCategory">Technology</p>
                                <strong id="previewPrice">£0.00</strong>
                            </div>
                        </div>
                    </div>

                    <div class="seller-panel">
                        <h2>Quick checklist</h2>
                        <ul class="seller-checklist">
                            <li>Use a searchable title</li>
                            <li>Choose the correct category</li>
                            <li>Upload both images</li>
                            <li>Include condition details</li>
                        </ul>
                    </div>
                </div>

                <div class="seller-actions">
                    <button type="button" class="secondary-button">Save Draft</button>
                    <button type="button" class="secondary-button">Preview Listing</button>
                    <button type="submit" class="primary-button seller-submit">Publish Listing</button>
                </div>
            </form>
        </section>
    </main>

    <div id="how-to-sell-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:2000;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:14px;width:90%;max-width:860px;height:80vh;display:flex;flex-direction:column;overflow:hidden;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #eee;">
                <h2 style="margin:0;font-size:1.1rem;">How to Create a Listing</h2>
                <button onclick="document.getElementById('how-to-sell-modal').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#666;">&times;</button>
            </div>
            <iframe src="https://scribehow.com/embed/How_To_Create_A_New_Auction_Listing_On_Ibay__dp9HemTqTxq0HDEbH8zIdA" style="flex:1;border:none;" allowfullscreen></iframe>
        </div>
    </div>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

    <script>
        function previewUpload(inputId, previewId, plusId, labelId, isMain) {
            const input   = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const plus    = document.getElementById(plusId);
            const label   = document.getElementById(labelId);

            input.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    plus.style.display    = 'none';
                    label.style.display   = 'none';

                    if (isMain) {
                        const mainPreview  = document.getElementById('previewMainImage');
                        const placeholder  = document.getElementById('previewPlaceholder');
                        mainPreview.src    = e.target.result;
                        mainPreview.style.display  = 'block';
                        placeholder.style.display  = 'none';
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        previewUpload('image1', 'preview1', 'plus1', 'label1', true);
        previewUpload('image2', 'preview2', 'plus2', 'label2', false);
    </script>

</body>
</html>