<?php
$conn = new mysqli("localhost", "root", "", "traffic_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>