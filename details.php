<?php
include("includes/check.php");
include("connection.php");

$userId  = $_SESSION['userId'];
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname    = mysqli_real_escape_string($conn, trim($_POST['firstname']));
    $surname      = mysqli_real_escape_string($conn, trim($_POST['surname']));
    $email        = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username     = mysqli_real_escape_string($conn, trim($_POST['username']));
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $address      = mysqli_real_escape_string($conn, trim($_POST['address']));
    $postcode     = mysqli_real_escape_string($conn, trim($_POST['postcode']));

    $usernameCheck = mysqli_query($conn, "SELECT userId FROM iBayMembers WHERE username = '$username' AND userId != $userId");
    $emailCheck    = mysqli_query($conn, "SELECT userId FROM iBayMembers WHERE email = '$email' AND userId != $userId");

    if (mysqli_num_rows($usernameCheck) > 0) {
        $error = "That username is already taken.";
    } elseif (mysqli_num_rows($emailCheck) > 0) {
        $error = "That email address is already in use.";
    } elseif (!empty($_POST['new_password'])) {
        $userRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM iBayMembers WHERE userId = $userId"));
        if (!password_verify($_POST['current_password'], $userRow['password'])) {
            $error = "Current password is incorrect.";
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = "New passwords do not match.";
        } elseif (strlen($_POST['new_password']) < 8) {
            $error = "New password must be at least 8 characters.";
        } else {
            $hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE iBayMembers SET firstname='$firstname', surname='$surname', email='$email', username='$username', phone_number='$phone_number', address='$address', postcode='$postcode', password='$hash' WHERE userId=$userId");
            $_SESSION['firstname'] = $firstname;
            $success = "Account updated successfully.";
        }
    } else {
        mysqli_query($conn, "UPDATE iBayMembers SET firstname='$firstname', surname='$surname', email='$email', username='$username', phone_number='$phone_number', address='$address', postcode='$postcode' WHERE userId=$userId");
        $_SESSION['firstname'] = $firstname;
        $success = "Account updated successfully.";
    }
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM iBayMembers WHERE userId = $userId"));
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
    <?php include("includes/navbar.php"); ?>
</header>

<main class="account-page">
    <div style="max-width:700px; margin:0 auto; padding:28px 0;">

    <div style="max-width:700px; margin:0 auto; padding:14px 0;">

        <div style="display:flex; align-items:center; gap:16px; margin-bottom:12px;">
            <a href="account.php" class="secondary-button" style="width:auto; padding:10px 16px;">&#8592; Back</a>
            <h1 style="font-size:1.8rem;">Account Details</h1>
        </div>

        <?php if ($success): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="POST" action="details.php" id="detailsForm">
            <input type="hidden" id="hidden-firstname"    name="firstname"    value="<?= htmlspecialchars($user['firstname']) ?>">
            <input type="hidden" id="hidden-surname"      name="surname"      value="<?= htmlspecialchars($user['surname']) ?>">
            <input type="hidden" id="hidden-email"        name="email"        value="<?= htmlspecialchars($user['email']) ?>">
            <input type="hidden" id="hidden-username"     name="username"     value="<?= htmlspecialchars($user['username']) ?>">
            <input type="hidden" id="hidden-phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">
            <input type="hidden" id="hidden-address"      name="address"      value="<?= htmlspecialchars($user['address'] ?? '') ?>">
            <input type="hidden" id="hidden-postcode"     name="postcode"     value="<?= htmlspecialchars($user['postcode'] ?? '') ?>">

            <div class="details-section">
                <h2>Personal Information</h2>
                <?php
                $fields = [
                    'firstname'    => ['label' => 'First Name',    'type' => 'text'],
                    'surname'      => ['label' => 'Surname',        'type' => 'text'],
                    'email'        => ['label' => 'Email',          'type' => 'email'],
                    'username'     => ['label' => 'Username',       'type' => 'text'],
                    'phone_number' => ['label' => 'Phone Number',   'type' => 'text'],
                    'address'      => ['label' => 'Address',        'type' => 'text'],
                    'postcode'     => ['label' => 'Postcode',       'type' => 'text'],
                ];
                foreach ($fields as $key => $info):
                    $val     = htmlspecialchars($user[$key] ?? '');
                    $display = $val ?: '<span style="color:#aaa;">Not set</span>';
                ?>
                <div class="detail-row" id="row-<?= $key ?>">
                    <span class="detail-label"><?= $info['label'] ?></span>
                    <span class="detail-value" id="val-<?= $key ?>"><?= $display ?></span>
                    <input type="<?= $info['type'] ?>" class="detail-input" id="inp-<?= $key ?>" value="<?= $val ?>">
                    <button type="button" class="edit-btn"   onclick="startEdit('<?= $key ?>')">Edit</button>
                    <button type="submit" class="save-btn"   id="save-<?= $key ?>">Save</button>
                    <button type="button" class="cancel-btn" id="cancel-<?= $key ?>" onclick="cancelEdit('<?= $key ?>')">Cancel</button>
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

            <form method="POST" action="details.php">
                <input type="hidden" name="firstname"    value="<?= htmlspecialchars($user['firstname']) ?>">
                <input type="hidden" name="surname"      value="<?= htmlspecialchars($user['surname']) ?>">
                <input type="hidden" name="email"        value="<?= htmlspecialchars($user['email']) ?>">
                <input type="hidden" name="username"     value="<?= htmlspecialchars($user['username']) ?>">
                <input type="hidden" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">
                <input type="hidden" name="address"      value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                <input type="hidden" name="postcode"     value="<?= htmlspecialchars($user['postcode'] ?? '') ?>">

                <div class="password-fields" id="passwordFields">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" placeholder="Enter current password">
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" placeholder="At least 8 characters">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Repeat new password">
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" class="primary-button" style="width:auto; padding:10px 24px;">Save Password</button>
                        <button type="button" class="secondary-button" style="width:auto; padding:10px 24px;" onclick="togglePassword()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>

<?php include("includes/footer.php"); ?>

<script>
function startEdit(field) {
    const row = document.getElementById('row-' + field);
    document.getElementById('val-'    + field).style.display = 'none';
    document.getElementById('inp-'    + field).style.display = 'block';
    document.getElementById('save-'   + field).style.display = 'inline-block';
    document.getElementById('cancel-' + field).style.display = 'inline-block';
    row.querySelector('.edit-btn').style.display = 'none';
    document.getElementById('inp-' + field).focus();
}

function cancelEdit(field) {
    const row = document.getElementById('row-' + field);
    const hidden = document.getElementById('hidden-' + field);
    document.getElementById('inp-'    + field).value         = hidden ? hidden.value : '';
    document.getElementById('val-'    + field).style.display = '';
    document.getElementById('inp-'    + field).style.display = 'none';
    document.getElementById('save-'   + field).style.display = 'none';
    document.getElementById('cancel-' + field).style.display = 'none';
    row.querySelector('.edit-btn').style.display = '';
}

function togglePassword() {
    const fields = document.getElementById('passwordFields');
    fields.classList.toggle('open');
}

document.querySelectorAll('.save-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const field = this.id.replace('save-', '');
        const hidden = document.getElementById('hidden-' + field);
        if (hidden) hidden.value = document.getElementById('inp-' + field).value;
    });
});
</script>

</body>
</html>
