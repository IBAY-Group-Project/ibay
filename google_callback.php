<?php
session_start();
include("connection.php");

include("config.php");

if (!isset($_GET['code'])) {
    header("Location: login.php");
    exit();
}

// Exchange code for access token
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'code'          => $_GET['code'],
        'client_id'     => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri'  => GOOGLE_REDIRECT_URI,
        'grant_type'    => 'authorization_code',
    ]),
    CURLOPT_RETURNTRANSFER => true,
]);
$tokenResponse = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($tokenResponse['access_token'])) {
    header("Location: login.php");
    exit();
}

// Get user profile
$ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $tokenResponse['access_token']],
    CURLOPT_RETURNTRANSFER => true,
]);
$user = json_decode(curl_exec($ch), true);
curl_close($ch);

$email     = mysqli_real_escape_string($conn, $user['email']);
$firstname = mysqli_real_escape_string($conn, $user['given_name'] ?? '');
$surname   = mysqli_real_escape_string($conn, $user['family_name'] ?? '');

// Check if account already exists
$existing = mysqli_query($conn, "SELECT * FROM iBayMembers WHERE email = '$email'");

if (mysqli_num_rows($existing) === 1) {
    $row = mysqli_fetch_assoc($existing);
    $_SESSION['userId']    = $row['userId'];
    $_SESSION['firstname'] = $row['firstname'];
    $_SESSION['email']     = $row['email'];
    $_SESSION['is_admin']  = $row['is_admin'];
    header("Location: /ibay/index.php");
    exit();
}

// New user — auto-generate username from email prefix
$base     = mysqli_real_escape_string($conn, strtolower(explode('@', $user['email'])[0]));
$username = $base;
if (mysqli_num_rows(mysqli_query($conn, "SELECT userId FROM iBayMembers WHERE username = '$username'")) > 0) {
    $username = $base . rand(100, 999);
}

$sql = "INSERT INTO iBayMembers (firstname, surname, email, username, password, address, postcode, phone_number)
        VALUES ('$firstname', '$surname', '$email', '$username', '', '', '', '')";

if (mysqli_query($conn, $sql)) {
    $id = mysqli_insert_id($conn);
    $_SESSION['userId']    = $id;
    $_SESSION['firstname'] = $user['given_name'] ?? '';
    $_SESSION['email']     = $user['email'];
    $_SESSION['is_admin']  = 0;
    header("Location: /ibay/index.php");
    exit();
} else {
    die("Error creating account: " . mysqli_error($conn));
}
?>
