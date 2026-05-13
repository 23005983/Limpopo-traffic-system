<?php
include 'db.php';

if (!isset($_GET['token'])) {
    die("Invalid reset link.");
}

$token = $_GET['token'];

/*
=========================================
CHECK TOKEN VALIDITY
=========================================
*/
$result = $conn->query("
    SELECT * FROM users
    WHERE reset_token='$token'
    AND token_expiry > NOW()
");

if ($result->num_rows == 0) {
    die("Reset link is invalid or expired.");
}

$user = $result->fetch_assoc();

/*
=========================================
HANDLE PASSWORD RESET
=========================================
*/
if (isset($_POST['update_password'])) {

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $conn->query("
            UPDATE users
            SET password='$hashed_password',
                reset_token=NULL,
                token_expiry=NULL
            WHERE id='{$user['id']}'
        ");

        $success = "Password reset successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css?v=10">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
</div>

<h2>Reset Password</h2>

<div class="container">

    <?php if (isset($success)) { ?>
        <p style="color:green;"><?php echo $success; ?></p>
        <a href="login.php" class="success-btn">Go to Login</a>
    <?php } else { ?>

        <?php if (isset($error)) { ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" autocomplete="off">

            <label>New Password</label>
            <div class="password-box">
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    placeholder="Enter New Password"
                    autocomplete="new-password"
                    required
                >
                <span class="toggle-eye" onclick="togglePassword('new_password','eye1')">
                    <span id="eye1" class="material-icons">visibility</span>
                </span>
            </div>

            <label>Confirm Password</label>
            <div class="password-box">
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Confirm New Password"
                    autocomplete="new-password"
                    required
                >
                <span class="toggle-eye" onclick="togglePassword('confirm_password','eye2')">
                    <span id="eye2" class="material-icons">visibility</span>
                </span>
            </div>

            <button name="update_password">Update Password</button>

        </form>

    <?php } ?>

</div>

<script>
function togglePassword(fieldId, eyeId) {
    let passwordField = document.getElementById(fieldId);
    let eyeIcon = document.getElementById(eyeId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.innerHTML = "visibility_off";
    } else {
        passwordField.type = "password";
        eyeIcon.innerHTML = "visibility";
    }
}
</script>

</body>
</html>