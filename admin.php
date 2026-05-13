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

/*
=========================================
ONLY ADMIN EMAIL CAN ACCESS ADMIN PAGE
=========================================
*/
$admin_email = "vholithole@gmail.com"; // your admin email only

if ($_SESSION['email'] !== $admin_email) {
    echo "<script>
        alert('Access denied! Only admin can access this page.');
        window.location='dashboard.php';
    </script>";
    exit();
}

/*
=========================================
DELETE BOOKING
=========================================
*/
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $conn->query("DELETE FROM bookings WHERE id=$id");

    header("Location: admin.php");
    exit();
}

/*
=========================================
SHOW ALL BOOKINGS TO ADMIN ONLY
=========================================
*/
$result = $conn->query("
    SELECT 
        bookings.id,
        users.name,
        users.email,
        bookings.booking_type,
        bookings.booking_date,
        bookings.booking_time
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    ORDER BY bookings.booking_date ASC, bookings.booking_time ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
    <p style="color: gray;">Admin Panel</p>
</div>

<h2>All Bookings (Admin Only)</h2>

<div class="container-wide">

<table border="1" width="100%" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>User Name</th>
        <th>User Email</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Action</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['booking_type']); ?></td>
        <td><?php echo $row['booking_date']; ?></td>
        <td><?php echo $row['booking_time']; ?></td>
        <td>
            <a href="admin.php?delete=<?php echo $row['id']; ?>"
               class="delete-btn"
               onclick="return confirm('Are you sure you want to delete this booking?')">
               Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

<br>
<a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

</body>
</html>