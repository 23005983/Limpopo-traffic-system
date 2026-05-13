<?php
include 'db.php';
date_default_timezone_set('Africa/Johannesburg');

$date = $_GET['date'];
$today = date('Y-m-d');
$currentHour = (int)date('H');

if ($date == $today) {
    $startHour = max(8, $currentHour + 1);
} else {
    $startHour = 8;
}

$endHour = 16;

echo '<option value="">Select Time</option>';

for ($h = $startHour; $h <= $endHour; $h++) {
    $slot = sprintf("%02d:00:00", $h);

    $check = $conn->query("
        SELECT id FROM bookings
        WHERE booking_date='$date'
        AND booking_time='$slot'
    ");

    if ($check->num_rows == 0) {
        echo "<option value='$slot'>$slot</option>";
    }
}
?>