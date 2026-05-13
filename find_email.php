<?php
include 'db.php';

if (isset($_POST['find_email'])) {

    $id_number = $_POST['id_number'];

    $result = $conn->query("
        SELECT email FROM users
        WHERE id_number='$id_number'
    ");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $success = "Your registered email is: " . $user['email'];
    } else {
        $error = "No account found with that ID number.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find My Email - Limpopo Traffic System</title>
    <link rel="stylesheet" href="style.css?v=13">
</head>
<body>

<div style="text-align:center; margin-top:30px;">
    <img src="logo.png" width="80"><br>
    <h1>Limpopo Traffic System</h1>
    <p style="color: gray;">Recover Forgotten Email</p>
</div>

<h2>Find My Registered Email</h2>

<div class="container">

    <?php if (isset($success)) { ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <label>ID Number</label>
        <input 
            type="text" 
            name="id_number" 
            placeholder="Enter Your ID Number" 
            required
        >

        <button type="submit" name="find_email">Find My Email</button>

    </form>

    <br>

    <a href="login.php" class="success-btn">
        Back to Login
    </a>

</div>

</body>
</html>