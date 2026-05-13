<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        // SAVE USER SESSION
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css?v=8">

    <!-- Material Icons (same style as your screenshot) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
    <p style="color: gray;">Developed for Limpopo Department of Transport</p>
</div>

<h2>User Login</h2>

<div class="container">

    <?php if (isset($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter Email" required>

        <label>Password</label>

        <div class="password-box">
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Enter Password" 
                required
            >

            <span class="toggle-eye" onclick="togglePassword()">
                <span id="eyeIcon" class="material-icons">visibility</span>
            </span>
        </div>

        <button name="login">Login</button>
<p style="margin-top:10px;">
    <a href="forgot_password.php">Forgot Password?</a>
</p>
        <p>
            Don’t have an account? 
            <a href="register.php">Register here</a>
        </p>
<p>
    Forgot your email? 
    <a href="find_email.php">Find it here</a>
</p>

    </form>

</div>

<script>
function togglePassword() {
    let password = document.getElementById("password");
    let eyeIcon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        eyeIcon.innerHTML = "visibility_off";
    } else {
        password.type = "password";
        eyeIcon.innerHTML = "visibility";
    }
}
</script>

</body>
</html>