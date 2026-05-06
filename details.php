<?php
include("includes/check.php");
include("connection.php");

$userId = $_SESSION['userId'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname    = mysqli_real_escape_string($conn, trim($_POST['firstname']));
    $surname      = mysqli_real_escape_string($conn, trim($_POST['surname']));
    $email        = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username     = mysqli_real_escape_string($conn, trim($_POST['username']));
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $address      = mysqli_real_escape_string($conn, trim($_POST['address']));
    $postcode     = mysqli_real_escape_string($conn, trim($_POST['postcode']));

    $check = mysqli_query($conn, "SELECT userId FROM iBayMembers WHERE username = '$username' AND userId != $userId");
    if (mysqli_num_rows($check) > 0) {
        $error = "That username is already taken.";
    } else {
        $sql = "UPDATE iBayMembers SET
                    firstname = '$firstname',
                    surname = '$surname',
                    email = '$email',
                    username = '$username',
                    phone_number = '$phone_number',
                    address = '$address',
                    postcode = '$postcode'
                WHERE userId = $userId";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['firstname'] = $firstname;
            $success = "Account details updated successfully.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE userId = $userId");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBay - Account Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-card { padding: 24px; }
        .auth-card h1 { font-size: 1.6rem; margin-bottom: 4px; }
        .auth-subtitle { margin-bottom: 16px; }
        .form-group { margin-bottom: 10px; gap: 4px; }
        .auth-form .primary-button { margin-top: 6px; }
    </style>
</head>
<body>

<header class="site-header">
    <?php include("includes/navbar.php"); ?>
</header>

<main class="auth-page">
    <section class="auth-card">
        <h1>Account Details</h1>
        <p class="auth-subtitle">Keep your profile up to date so you can buy and sell.</p>

        <?php if ($success): ?>
            <p style="color:green;margin-bottom:1rem;"><?= $success ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color:red;margin-bottom:1rem;"><?= $error ?></p>
        <?php endif; ?>

        <form class="auth-form" action="details.php" method="post">

            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">First name</label>
                    <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($user['surname']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone number</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>" placeholder="First line of your address" required>
            </div>

            <div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="text" id="postcode" name="postcode" value="<?= htmlspecialchars($user['postcode']) ?>" required>
            </div>

            <button type="submit" class="primary-button">Save Changes</button>

            <p class="auth-switch">
                <a href="account.php">Back to My Account</a>
            </p>

        </form>
    </section>
</main>

<footer class="site-footer">
    <p>&copy; 2026 iBay Marketplace. All rights reserved.</p>
</footer>

</body>
</html>
