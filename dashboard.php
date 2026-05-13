<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/*
=========================================
DEFINE ADMIN EMAIL
=========================================
*/
$admin_email = "vholithole@gmail.com";
$is_admin = ($_SESSION['email'] == $admin_email);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div style="text-align:center; margin-bottom:20px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
</div>

<!-- NAVBAR -->
<div style="background:green; padding:15px;">

    <a href="dashboard.php" style="color:white; margin:10px;">Home</a>

    <?php if ($is_admin): ?>
        <!-- ADMIN ONLY -->
        <a href="admin.php" style="color:white; margin:10px;">Admin</a>
    <?php else: ?>
        <!-- NORMAL USERS ONLY -->
        <a href="book.php" style="color:white; margin:10px;">Book</a>
        <a href="my_bookings.php" style="color:white; margin:10px;">My Bookings</a>
    <?php endif; ?>

    <!-- EVERYONE CAN ACCESS PROFILE -->
    <a href="profile.php" style="color:white; margin:10px;">Profile</a>

    <a href="logout.php" style="color:white; margin:10px;">Logout</a>

</div><div class="container">

    <?php if ($is_admin): ?>
        <h2>Welcome Admin <?php echo $_SESSION['name']; ?> 👑</h2>
        <p>You can inspect and manage all citizen bookings.</p>
    <?php else: ?>
        <h2>Welcome <?php echo $_SESSION['name']; ?> 🎉</h2>
        <p>You can book and view your own appointments.</p>
    <?php endif; ?>

</div>

</body>
</html>