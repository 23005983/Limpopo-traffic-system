<?php
include 'db.php';

// Handle register
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $id_number = $_POST['id_number'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, id_number, phone, address) 
                VALUES ('$name', '$email', '$hashed_password', '$id_number', '$phone', '$address')";

        if ($conn->query($sql)) {
            $success = "Registered successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css?v=9">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<div style="text-align:center; margin-bottom:20px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
</div>

<h2>Register</h2>

<div class="container">

    <?php if (isset($success)) { ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <!-- autocomplete off prevents saved login autofill -->
    <form method="POST" autocomplete="off">

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter Name" autocomplete="off" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter Email" autocomplete="off" required>

        <label>ID Number</label>
        <input type="text" name="id_number" placeholder="Enter ID Number" autocomplete="off" required>

        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="Enter Phone Number" autocomplete="off" required>

        <label>Home Address</label>
        <input type="text" name="address" placeholder="Enter Home Address" autocomplete="off" required>

        <label>Password</label>
        <div class="password-box">
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Enter Password" 
                autocomplete="new-password"
                required
            >
            <span class="toggle-eye" onclick="togglePassword('password', 'eye1')">
                <span id="eye1" class="material-icons">visibility</span>
            </span>
        </div>

        <label>Confirm Password</label>
        <div class="password-box">
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                placeholder="Re-enter Password" 
                autocomplete="new-password"
                required
            >
            <span class="toggle-eye" onclick="togglePassword('confirm_password', 'eye2')">
                <span id="eye2" class="material-icons">visibility</span>
            </span>
        </div>

        <button name="register">Register</button>

    </form>

</div>

<script>
function togglePassword(fieldId, eyeId) {
    let passwordField = document.getElementById(fieldId);
    let eyeIcon = document.getElementById(eyeId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.innerHTML = "visibility_off";
    } else {
        passwordField.type = "password";
        eyeIcon.innerHTML = "visibility";
    }
}
</script>

</body>
</html>