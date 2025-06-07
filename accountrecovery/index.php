<?php
session_start();
include('../includes/connection.php');
require_once __DIR__ . '/../includes/email-template.php';

$config = require '../includes/config.php';
require_once __DIR__ . '/../includes/send-email.php';
// Logo file
$logoFilePath = $config['logo_url'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $dsn->real_escape_string($_POST['email']);

    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $dsn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $dsn->query("UPDATE users SET resetKey = '$token', resetKeyExpiry = '$expires_at' WHERE userId = {$user['userId']}");

        $resetLink = "{$config['officialWebsite']}reset/?email={$user['email']}&token=$token";
        $emailBody      = "<p>Hello <strong>{$user['firstName']}</strong>,</p>
             <p>We received a request to reset your password. Click the link below to proceed:</p>
             <p><a href='$resetLink'>Reset Password</a></p>
             <p>If the link above does not directly open click here {$resetLink} </p>
             <p>This link will expire in 1 hour.</p>
             <p>Best Regards,
             <br><strong>Medibelle Support Team</strong></p>
             ";
        // $emailBody = renderEmailTemplate("Password Reset Request", $body);
        $name = $user['firstName'] . ' ' . $user['lastName'];
        if (sendEmail($email, $name, 'Password Reset Email',$emailBody, $config, $logoFilePath)) {
            $success = "A password reset link has been sent to your email.";
        } else {
            $error = "Failed to send email. Try again later.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!-- HTML for email input form -->
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="shortcut icon" href="/cosmeticsstore/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="login-container shadow-lg p-5">
                <img src="/cosmeticsstore/images/logo.png" alt="Logo" class="logo img-fluid" width="300">
                <h3 class="text-center mb-4 mt-2">Forgot Password</h3>

                <?php if (isset($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if (isset($success)) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>


                <form method="post">
                    <div class="form-group mb-3">
                        <label for="email">Enter your email address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com"required>
                    </div>
                    <button class="btn btn-success w-100" type="submit" id="sendResetLinkBtn">
                        <span class="btn-text">Send Reset Link</span>
                    </button>


                </form>
                <div class="text-center m-2">
                    <a href="../">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>


</div>
<script src="/cosmeticsstore/assets/js/bootstrap.bundle.min.js"></script>
<script>
    // Send Reset Link Page
    const sendResetLinkBtn = document.getElementById('sendResetLinkBtn');
    if (sendResetLinkBtn) {
        sendResetLinkBtn.closest('form').addEventListener('submit', function () {
            sendResetLinkBtn.disabled = true;
            sendResetLinkBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        });
    }
    
</script>
</body>
</html>
