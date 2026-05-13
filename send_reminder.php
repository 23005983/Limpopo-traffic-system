<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

date_default_timezone_set('Africa/Johannesburg');

echo "🚀 Reminder system running...<br>";

$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

/*
=========================================
GET TODAY'S BOOKINGS NOT YET REMINDED
=========================================
*/
$sql = "
    SELECT 
        users.email,
        users.name,
        bookings.id,
        bookings.booking_type,
        bookings.booking_date,
        bookings.booking_time
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    WHERE bookings.booking_date = '$currentDate'
    AND bookings.reminder_sent = 0
";

$result = $conn->query($sql);

echo "📊 Rows found: " . $result->num_rows . "<br><br>";

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        /*
        =========================================
        CALCULATE 15 MINUTES BEFORE APPOINTMENT
        =========================================
        Example:
        Appointment: 10:00
        Reminder: 09:45
        */
        $bookingTime = $row['booking_time'];
        $reminderTime = date('H:i:s', strtotime($bookingTime) - 900); // 900 sec = 15 min

        /*
        =========================================
        SEND ONLY IF CURRENT TIME IS BETWEEN:
        reminderTime AND bookingTime
        =========================================
        */
        if ($currentTime >= $reminderTime && $currentTime < $bookingTime) {

            echo "📨 Sending reminder to: " . $row['email'] . "<br>";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;

                /*
                =========================================
                USE SAME GMAIL AS YOUR BOOK.PHP
                =========================================
                */
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
                $mail->addAddress($row['email'], $row['name']);

                $mail->isHTML(true);
                $mail->Subject = 'Appointment Reminder';

                $mail->Body = "
                    Hello {$row['name']},<br><br>

                    This is a reminder that your appointment is in <b>15 minutes</b>.<br><br>

                    <b>Service:</b> {$row['booking_type']}<br>
                    <b>Date:</b> {$row['booking_date']}<br>
                    <b>Time:</b> {$row['booking_time']}<br><br>

                    Please arrive on time.<br><br>

                    Limpopo Traffic System
                ";

                $mail->send();

                /*
                =========================================
                MARK REMINDER AS SENT
                =========================================
                Prevents duplicate reminders
                */
                $conn->query("
                    UPDATE bookings
                    SET reminder_sent = 1
                    WHERE id = {$row['id']}
                ");

                echo "✅ Reminder sent successfully<br><br>";

            } catch (Exception $e) {
                echo "❌ Email Error: {$mail->ErrorInfo}<br><br>";
            }

        } else {

            echo "⏳ Not time yet for: " . $row['email'] .
                 " (Reminder at $reminderTime, Booking at $bookingTime)<br>";

        }
    }

} else {
    echo "⚠️ No pending reminders for today.";
}
?>