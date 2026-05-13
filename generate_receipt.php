<?php
session_start();
include 'db.php';

require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');

date_default_timezone_set('Africa/Johannesburg');

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
CHECK BOOKING ID
=========================================
*/
if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$booking_id = $_GET['id'];

/*
=========================================
FETCH USER BOOKING ONLY
=========================================
*/
$result = $conn->query("
    SELECT bookings.*, users.name, users.email
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    WHERE bookings.id='$booking_id'
    AND bookings.user_id='$user_id'
");

if ($result->num_rows == 0) {
    die("Booking not found.");
}

$row = $result->fetch_assoc();

/*
=========================================
GENERATE QR CODE
=========================================
IMPORTANT: USE NGROK LINK
=========================================
*/
$qrData = "https://magnesium-opt-detonator.ngrok-free.dev/traffic-system/verify.php?id=" . $row['id'];

$qrFile = 'temp_qr_' . $row['id'] . '.png';

QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 5);

/*
=========================================
CREATE PDF
=========================================
*/
$pdf = new FPDF();
$pdf->AddPage();

/* TITLE */
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Limpopo Traffic System Booking Receipt',0,1,'C');

$pdf->Ln(10);

/* USER DETAILS */
$pdf->SetFont('Arial','',12);

$pdf->Cell(50,10,'Full Name:',0,0);
$pdf->Cell(100,10,$row['name'],0,1);

$pdf->Cell(50,10,'Email:',0,0);
$pdf->Cell(100,10,$row['email'],0,1);

$pdf->Cell(50,10,'Service:',0,0);
$pdf->Cell(100,10,$row['booking_type'],0,1);

$pdf->Cell(50,10,'Date:',0,0);
$pdf->Cell(100,10,$row['booking_date'],0,1);

$pdf->Cell(50,10,'Time:',0,0);
$pdf->Cell(100,10,$row['booking_time'],0,1);

$pdf->Cell(50,10,'Receipt No:',0,0);
$pdf->Cell(100,10,'LTR-'.$row['id'],0,1);

$pdf->Ln(10);

/*
=========================================
ADD QR CODE TO PDF
=========================================
*/
$pdf->Image($qrFile, 140, 70, 50, 50);

$pdf->Ln(30);

/* FOOTER TEXT */
$pdf->SetFont('Arial','I',11);
$pdf->Cell(190,10,'Scan QR code to verify booking',0,1,'C');
$pdf->Cell(190,10,'Please arrive 15 minutes before your appointment.',0,1,'C');

/*
=========================================
OUTPUT PDF
=========================================
*/
$pdf->Output();

/*
=========================================
DELETE TEMP QR FILE
=========================================
*/
if (file_exists($qrFile)) {
    unlink($qrFile);
}
?>