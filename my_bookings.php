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
DELETE BOOKING
ONLY DELETE USER'S OWN BOOKING
=========================================
*/
if (isset($_GET['delete'])) {
    $booking_id = $_GET['delete'];

    $delete = $conn->query("
        DELETE FROM bookings 
        WHERE id='$booking_id' 
        AND user_id='$user_id'
    ");

    if ($delete) {
        $success = "Booking deleted successfully.";
    } else {
        $error = "Failed to delete booking.";
    }
}

/*
=========================================
FETCH ONLY CURRENT USER BOOKINGS
=========================================
*/
$result = $conn->query("
    SELECT id, booking_type, booking_date, booking_time
    FROM bookings
    WHERE user_id='$user_id'
    ORDER BY booking_date ASC, booking_time ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css?v=12">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
    <p style="color: gray;">My Bookings</p>
</div>

<h2>My Appointments</h2>

<div class="container-wide">

<?php if (isset($success)) { ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php } ?>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php } ?>

<table border="1" width="100%" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Action</th>
        <th>Receipt</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['booking_type']); ?></td>
        <td><?php echo $row['booking_date']; ?></td>
        <td><?php echo $row['booking_time']; ?></td>

        <td>
            <a 
                href="my_bookings.php?delete=<?php echo $row['id']; ?>"
                onclick="return confirm('Are you sure you want to cancel this booking?')"
                style="color:red; font-weight:bold; text-decoration:none;"
            >
                Delete
            </a>
        </td>

        <td>
            <a 
                href="generate_receipt.php?id=<?php echo $row['id']; ?>" 
                target="_blank"
                style="color:green; font-weight:bold; text-decoration:none;"
            >
                Download PDF
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