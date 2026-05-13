<?php
session_start();
include 'db.php';

/*
=========================================
CHECK LOGIN
=========================================
*/
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*
=========================================
FETCH USER INFO
=========================================
*/
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

/*
=========================================
UPDATE PROFILE
=========================================
*/
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $new_password = $_POST['password'];

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $conn->query("
            UPDATE users 
            SET name='$name', phone='$phone', password='$hashed_password'
            WHERE id='$user_id'
        ");
    } else {
        $conn->query("
            UPDATE users 
            SET name='$name', phone='$phone'
            WHERE id='$user_id'
        ");
    }

    $_SESSION['name'] = $name;

    $success = "Profile updated successfully!";

    // Refresh user data
    $result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
    <p style="color: gray;">My Profile</p>
</div>

<h2>Update Profile</h2>

<div class="container">

    <?php if (isset($success)) { ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php } ?>

    <form method="POST">

        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

        <label>Email (cannot change):</label>
        <input type="email" value="<?php echo $user['email']; ?>" disabled>

        <label>Phone Number:</label>
        <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>

        <label>New Password (optional):</label>
        <input type="password" name="password" placeholder="Leave blank to keep current password">

        <button name="update">Update Profile</button>

    </form>

    <br>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

</body>
</html>