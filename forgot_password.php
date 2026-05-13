<?php
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

if (isset($_POST['reset'])) {

    $email = $_POST['email'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows > 0) {

        $token = md5(rand());
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $conn->query("
            UPDATE users 
            SET reset_token='$token', token_expiry='$expiry'
            WHERE email='$email'
        ");

        $resetLink = "http://10.113.10.7/traffic-system/reset_password.php?token=$token";

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
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';

            $mail->Body = "
                Click the link below to reset your password:<br><br>
                <a href='$resetLink'>$resetLink</a><br><br>
                This link expires in 1 hour.
            ";

            $mail->send();

            $success = "Reset link sent to your email.";

        } catch (Exception $e) {
            $error = "Email could not be sent.";
        }

    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Forgot Password</h2>

<div class="container">

<?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <label>Email</label>
    <input type="email" name="email" required>
    <button name="reset">Send Reset Link</button>
</form>

</div>

</body>
</html>