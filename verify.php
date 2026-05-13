<?php
include 'db.php';

if (!isset($_GET['id'])) {
    echo "❌ Invalid QR Code";
    exit();
}

$id = $_GET['id'];

$result = $conn->query("
    SELECT users.name, users.email, bookings.*
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    WHERE bookings.id = '$id'
");

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    // CHECK STATUS
    if ($row['status'] == 'used') {

        echo "<h2 style='color:red;'>❌ Already Used</h2>";
        echo "This booking has already been used.";

    } else {

        // MARK AS USED
        $conn->query("
            UPDATE bookings
            SET status = 'used'
            WHERE id = '$id'
        ");

        echo "<h2 style='color:green;'>✅ Booking Verified</h2>";
        echo "<b>Name:</b> " . $row['name'] . "<br><br>";
        echo "<b>Service:</b> " . $row['booking_type'] . "<br><br>";
        echo "<b>Date:</b> " . $row['booking_date'] . "<br><br>";
        echo "<b>Time:</b> " . $row['booking_time'] . "<br><br>";

        echo "<p style='color:green; font-weight:bold;'>Checked-in successfully</p>";
    }

} else {
    echo "<h2 style='color:red;'>❌ Invalid Booking</h2>";
}
?>