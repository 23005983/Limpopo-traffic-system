<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

date_default_timezone_set('Africa/Johannesburg');

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// HANDLE BOOKING
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book'])) {

    $type = $_POST['type'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $user_id = $_SESSION['user_id'];

    $today = date('Y-m-d');

    if ($date < $today) {
        $error = "You cannot book a past date!";
    } else {

        // CHECK IF USER ALREADY HAS BOOKING SAME DATE
        $checkUserBooking = $conn->query("
            SELECT id FROM bookings
            WHERE user_id='$user_id'
            AND booking_date='$date'
        ");

        if ($checkUserBooking->num_rows > 0) {
            $error = "You already have an appointment booked for this date. Please choose another date.";
        } else {

            // CHECK IF SLOT ALREADY TAKEN
            $checkSlot = $conn->query("
                SELECT id FROM bookings
                WHERE booking_date='$date'
                AND booking_time='$time'
            ");

            if ($checkSlot->num_rows > 0) {
                $error = "This time slot is already booked. Please choose another.";
            } else {

                // INSERT BOOKING
                $sql = "INSERT INTO bookings (user_id, booking_type, booking_date, booking_time)
                        VALUES ('$user_id', '$type', '$date', '$time')";

                if ($conn->query($sql)) {

                    // GET USER INFO
                    $userQuery = $conn->query("SELECT email, name FROM users WHERE id='$user_id'");
                    $user = $userQuery->fetch_assoc();

                    // SEND EMAIL CONFIRMATION
                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'vholithole@gmail.com';
                        $mail->Password = 'dbzmgkyweeutudyd';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->SMTPOptions = [
                            'ssl' => [
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            ]
                        ];

                        $mail->setFrom('vholithole@gmail.com', 'Limpopo Traffic System');
                        $mail->addAddress($user['email'], $user['name']);

                        $mail->isHTML(true);
                        $mail->Subject = 'Booking Confirmation';

                        $mail->Body = "
                            Hello {$user['name']},<br><br>
                            Your booking was successful.<br>
                            <b>Service:</b> $type<br>
                            <b>Date:</b> $date<br>
                            <b>Time:</b> $time<br><br>
                            Please arrive on time.<br><br>
                            Limpopo Traffic System
                        ";

                        $mail->send();

                    } catch (Exception $e) {}

                    $success = "You are successfully booked. Your appointment is on $date at $time";

                } else {
                    $error = "Error: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book - Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css?v=12">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
    <p style="color: gray;">Developed for Limpopo Department of Transport</p>
</div>

<h2>Book Appointment</h2>

<div class="container">

<?php if (isset($success)) { ?>

    <p style="color:green;"><?php echo $success; ?></p>

    <a href="dashboard.php" class="success-btn">
        Back to Dashboard
    </a>

<?php } else { ?>

    <?php if (isset($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" id="bookingForm">

        <label>Select Service:</label>
        <select name="type" required>
            <option value="renewal">License Renewal</option>
            <option value="learner">Learner Test</option>
            <option value="driving">Driving Test</option>
        </select>

        <label>Select Date:</label>
        <input 
            type="date" 
            name="date" 
            id="booking_date"
            min="<?php echo date('Y-m-d'); ?>" 
            required 
            onchange="loadTimes()"
        >

        <label>Select Time:</label>
        <select name="time" id="time_slot" required>
            <option value="">Select Time</option>
        </select>

        <!-- Hidden submit button -->
        <button type="submit" name="book" id="realSubmitBtn" style="display:none;"></button>

        <!-- Visible popup trigger button -->
        <button type="button" onclick="openPopup()">Book</button>

    </form>

    <!-- POPUP -->
    <div id="bookingPopup" class="popup-overlay">
        <div class="popup-box">
            <h3>Confirm Booking</h3>
            <p>Are you sure you want to book this appointment?</p>

            <button type="button" onclick="submitBooking()">Yes, Book</button>
            <button type="button" onclick="closePopup()">Cancel</button>
        </div>
    </div>

<?php } ?>

</div>

<script>
function loadTimes() {
    let date = document.getElementById("booking_date").value;

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "get_times.php?date=" + date, true);

    xhr.onload = function() {
        document.getElementById("time_slot").innerHTML = this.responseText;
    };

    xhr.send();
}

function openPopup() {
    document.getElementById("bookingPopup").style.display = "flex";
}

function closePopup() {
    document.getElementById("bookingPopup").style.display = "none";
}

function submitBooking() {
    document.getElementById("realSubmitBtn").click();
}
</script>

</body>
</html>