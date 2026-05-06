<?php 
session_start();
include("php/connection.php");

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['publish'])) {

    $userId      = $_SESSION['userId'];
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $category    = mysqli_real_escape_string($conn, $_POST['category']);
    $condition   = mysqli_real_escape_string($conn, $_POST['condition']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $postage     = mysqli_real_escape_string($conn, $_POST['postage']);

    $sql = "INSERT INTO iBayItems (userId, title, category, `condition`, description, price, postage)
            VALUES ('$userId', '$title', '$category', '$condition', '$description', '$price', '$postage')";

    if (mysqli_query($conn, $sql)) {
        $itemId = mysqli_insert_id($conn);

        $imageFields = ['image1', 'image2', 'image3'];

        foreach ($imageFields as $field) {
            if ($_FILES[$field]['error'] === 0) {
                $fileName = $_FILES[$field]['name'];
                $tempName = $_FILES[$field]['tmp_name'];
                $fileSize = $_FILES[$field]['size'];

                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $validExts = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'heic'];

                if (!in_array($ext, $validExts)) {
                    echo "<script> alert('Invalid image type for " . $field . "'); </script>";
                    continue;
                }

                if ($fileSize > 5000000) {
                    echo "<script> alert('Image too large for $field'); </script>";
                    continue;
                }

                $newFileName = uniqid() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
                $uploadDir   = $_SERVER['DOCUMENT_ROOT'] . '/images/products/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($tempName, $destination)) {
                    $imagePath = mysqli_real_escape_string($conn, $newFileName);
                    $mimeType  = mime_content_type($destination);
                    $mimeType  = mysqli_real_escape_string($conn, $mimeType);

                    $imgSql = "INSERT INTO iBayImages (image, mimeType, imageSize, itemId)
                               VALUES ('$imagePath', '$mimeType', '$fileSize', '$itemId')";
                    mysqli_query($conn, $imgSql);
                }
            }
        }

        echo "<script> 
            alert('Listing published successfully!'); 
            document.location.href = 'index.php';
        </script>";

    } else {
        echo "<script> alert('Error: " . mysqli_error($conn) . "'); </script>";
    }
}
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
                <div class="search-bar">
                    <input type="text" placeholder="Search for items...">
                </div>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="basket.html" class="icon-button">??</a>
                <?php else: ?>
                    <a href="login.html" class="icon-button">??</a>
                    <a href="basket.html" class="icon-button">??</a>
                <?php endif; ?>
            </div>
        </div>

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
                        Complete all listing details below. Keep the form compact while making the preview easy to review.
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
                                <option>Used - Good</option>
                                <option>New</option>
                                <option>Like New</option>
                                <option>Used - Acceptable</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (&pound;)</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="postage">Postage</label>
                            <select id="postage" name="postage">
                                <option>Free postage</option>
                                <option>&pound;1.99</option>
                                <option>&pound;2.99</option>
                                <option>&pound;4.99</option>
                                <option>Collection only</option>
                            </select>
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
                        <h2>Upload 3 images</h2>
                        <p>Show all main facets of the product where possible.</p>

                        <div class="upload-grid">
                            <label class="upload-slot" for="image1">
                                <input type="file" id="image1" name="image1" accept="image/*" hidden>
                                <span class="upload-plus">+</span>
                                <span>Main view</span>
                            </label>

                            <label class="upload-slot" for="image2">
                                <input type="file" id="image2" name="image2" accept="image/*" hidden>
                                <span class="upload-plus">+</span>
                                <span>Side view</span>
                            </label>

                            <label class="upload-slot" for="image3">
                                <input type="file" id="image3" name="image3" accept="image/*" hidden>
                                <span class="upload-plus">+</span>
                                <span>Close-up</span>
                            </label>
                        </div>
                    </div>

                    <div class="seller-panel">
                        <h2>Live listing preview</h2>
                        <div class="listing-preview-card">
                            <div class="listing-preview-image">Preview</div>
                            <div class="listing-preview-content">
                                <h3 id="previewTitle">Your item title</h3>
                                <p id="previewCategory">Technology</p>
                                <strong id="previewPrice">&pound;0.00</strong>
                            </div>
                        </div>
                    </div>

                    <div class="seller-panel">
                        <h2>Quick checklist</h2>
                        <ul class="seller-checklist">
                            <li>Use a searchable title</li>
                            <li>Choose the correct category</li>
                            <li>Upload all 3 images</li>
                            <li>Include condition details</li>
                        </ul>
                    </div>
                </div>

                <div class="seller-actions">
                    <button type="button" class="secondary-button">Save Draft</button>
                    <button type="button" class="secondary-button">Preview Listing</button>
                    <button type="submit" name="publish" class="primary-button seller-submit">Publish Listing</button>
                </div>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>

</body>
</html>