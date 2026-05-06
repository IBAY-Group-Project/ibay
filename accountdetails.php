<?php
session_start();
include("php/connection.php");

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}

$userId  = $_SESSION['userId'];
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname    = mysqli_real_escape_string($conn, $_POST['firstname']);
    $surname      = mysqli_real_escape_string($conn, $_POST['surname']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $address      = mysqli_real_escape_string($conn, $_POST['address']);
    $postcode     = mysqli_real_escape_string($conn, $_POST['postcode']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    $emailCheck = mysqli_query($conn, "SELECT userId FROM iBayMembers WHERE email = '$email' AND userId != '$userId'");
    if (mysqli_num_rows($emailCheck) > 0) {
        $error = "That email address is already in use by another account.";
    } else {
        if (!empty($_POST['new_password'])) {
            $currentPassword = $_POST['current_password'];
            $newPassword     = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            $userResult      = mysqli_query($conn, "SELECT password FROM iBayMembers WHERE userId = '$userId'");
            $user            = mysqli_fetch_assoc($userResult);

            if (!password_verify($currentPassword, $user['password'])) {
                $error = "Current password is incorrect.";
            } else if ($newPassword !== $confirmPassword) {
                $error = "New passwords do not match.";
            } else if (strlen($newPassword) < 8) {
                $error = "New password must be at least 8 characters.";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE iBayMembers SET firstname='$firstname', surname='$surname', email='$email', address='$address', postcode='$postcode', phone_number='$phone_number', password='$hashedPassword' WHERE userId='$userId'");
                $_SESSION['firstname'] = $firstname;
                $success = "Account updated successfully.";
            }
        } else {
            mysqli_query($conn, "UPDATE iBayMembers SET firstname='$firstname', surname='$surname', email='$email', address='$address', postcode='$postcode', phone_number='$phone_number' WHERE userId='$userId'");
            $_SESSION['firstname'] = $firstname;
            $success = "Account updated successfully.";
        }
    }
}

$userResult = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE userId = '$userId'");
$user       = mysqli_fetch_assoc($userResult);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Account Details</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="top-header">
            <div class="logo"><a href="index.php">iBay</a></div>
            <nav class="top-nav">
                <a href="sell.php">Sell</a>
                <a href="account.php">Account</a>
                <a href="php/logout.php">Logout</a>
            </nav>
            <div class="header-actions">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Search for items...">
                    <button type="submit" class="search-submit-button">Search</button>
                </form>
                <a href="account.php" class="icon-button">&#128100;</a>
                <a href="basket.php" class="icon-button">&#128722;</a>
            </div>
        </div>
        <nav class="category-nav">
            <a href="search.php?category=Technology">Technology</a>
            <a href="search.php?category=Clothing">Clothing</a>
            <a href="search.php?category=Trading Cards">Trading Cards</a>
            <a href="search.php?category=Gardening">Gardening</a>
            <a href="search.php?category=Home">Home</a>
            <a href="search.php?category=Collectables">Collectables</a>
            <a href="search.php?category=Sports">Sports</a>
            <a href="search.php?category=Books">Books</a>
        </nav>
    </header>

    <main class="account-page">
        <div style="max-width: 700px; margin: 0 auto;">

            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <a href="account.php" class="secondary-button" style="width: auto; padding: 10px 16px;">&#8592; Back</a>
                <h1 style="font-size: 1.8rem;">Account Details</h1>
            </div>

            <?php if ($success): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if ($error):   ?><div class="alert-error"><?php echo $error; ?></div><?php endif; ?>

            <form method="POST" action="accountdetails.php" id="detailsForm">
                <input type="hidden" id="hidden-firstname"    name="firstname"    value="<?php echo htmlspecialchars($user['firstname']); ?>">
                <input type="hidden" id="hidden-surname"      name="surname"      value="<?php echo htmlspecialchars($user['surname']); ?>">
                <input type="hidden" id="hidden-email"        name="email"        value="<?php echo htmlspecialchars($user['email']); ?>">
                <input type="hidden" id="hidden-address"      name="address"      value="<?php echo htmlspecialchars($user['address']); ?>">
                <input type="hidden" id="hidden-postcode"     name="postcode"     value="<?php echo htmlspecialchars($user['postcode']); ?>">
                <input type="hidden" id="hidden-phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">

                <div class="details-section">
                    <h2>Personal Information</h2>
                    <?php
                    $fields = [
                        'firstname'    => ['label' => 'First Name',  'type' => 'text'],
                        'surname'      => ['label' => 'Surname',      'type' => 'text'],
                        'email'        => ['label' => 'Email',        'type' => 'email'],
                        'phone_number' => ['label' => 'Phone Number', 'type' => 'text'],
                        'address'      => ['label' => 'Address',      'type' => 'text'],
                        'postcode'     => ['label' => 'Postcode',     'type' => 'text'],
                    ];
                    foreach ($fields as $fieldKey => $fieldInfo):
                        $val     = htmlspecialchars($user[$fieldKey] ?? '');
                        $display = $val ?: '<span style="color:#aaa;">Not set</span>';
                    ?>
                        <div class="detail-row" id="row-<?php echo $fieldKey; ?>">
                            <span class="detail-label"><?php echo $fieldInfo['label']; ?></span>
                            <span class="detail-value" id="val-<?php echo $fieldKey; ?>"><?php echo $display; ?></span>
                            <input type="<?php echo $fieldInfo['type']; ?>" class="detail-input" id="inp-<?php echo $fieldKey; ?>" value="<?php echo $val; ?>">
                            <button type="button" class="edit-btn" onclick="startEdit('<?php echo $fieldKey; ?>')">Edit</button>
                            <button type="submit" class="save-btn" id="save-<?php echo $fieldKey; ?>">Save</button>
                            <button type="button" class="cancel-btn" id="cancel-<?php echo $fieldKey; ?>" onclick="cancelEdit('<?php echo $fieldKey; ?>')">Cancel</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </form>

            <div class="details-section">
                <h2>Password</h2>
                <div class="password-row">
                    <div>
                        <span class="detail-label">Password</span>
                        <span class="detail-value" style="margin-left:16px;">&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;</span>
                    </div>
                    <button type="button" class="edit-btn" onclick="togglePassword()">Change</button>
                </div>

                <form method="POST" action="accountdetails.php">
                    <input type="hidden" name="firstname"    value="<?php echo htmlspecialchars($user['firstname']); ?>">
                    <input type="hidden" name="surname"      value="<?php echo htmlspecialchars($user['surname']); ?>">
                    <input type="hidden" name="email"        value="<?php echo htmlspecialchars($user['email']); ?>">
                    <input type="hidden" name="address"      value="<?php echo htmlspecialchars($user['address']); ?>">
                    <input type="hidden" name="postcode"     value="<?php echo htmlspecialchars($user['postcode']); ?>">
                    <input type="hidden" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">

                    <div class="password-fields" id="passwordFields">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" placeholder="Enter current password">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm new password">
                        </div>
                        <div style="display:flex;gap:10px;">
                            <button type="submit" class="primary-button" style="width:auto;padding:10px 24px;">Save Password</button>
                            <button type="button" class="secondary-button" style="width:auto;padding:10px 24px;" onclick="togglePassword()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>