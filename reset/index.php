<?php
session_start();
$config = require '../includes/config.php';
include('../includes/connection.php');
require '../includes/send-email.php';       // Function to send emails
require '../includes/email-template.php';   // Email template renderer

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';
$validToken = false;
$userId = null;
$name = null;

if ($token && $email) {
    $stmt = $dsn->prepare("SELECT userId, firstName, resetKeyExpiry FROM users WHERE email = ? AND resetKey = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        if (strtotime($row['resetKeyExpiry']) >= time()) {
            $validToken = true;
            $userId = $row['userId'];
            $name = $row['firstName'];
        } else {
            $error = "The reset link has expired.";
        }
    } else {
        $error = "Invalid or already used reset link.";
    }
} else {
    $error = "Missing or invalid reset token.";
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'], $_POST['user_id'], $_POST['email'])) {
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];
    $userId = (int)$_POST['user_id'];
    $email = $_POST['email'];

    if ($newPass !== $confirmPass) {
        $error = "Passwords do not match.";
        $validToken = true;
    } elseif (strlen($newPass) < 6) {
        $error = "Password must be at least 6 characters long.";
        $validToken = true;
    } else {
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $dsn->query("UPDATE users SET password = '$hashed', resetKey = NULL, resetKeyExpiry = NULL WHERE userId = $userId");

        // Send confirmation email
        $emailBody = "<p>Hello <strong>{$name}</strong>,</p>
            <p>This is to confirm that your password was successfully changed.</p>
            <p>If you did not make this change, please reset your password immediately or contact support.</p>
            <p>Regards,<br>The {$config['supportTeam']}</p>";

        sendEmail($email, 'Password Changed', 'Password Change Notification', $emailBody, $config, $config['logo_url']);

        $success = "Your password has been updated successfully. <a href='../auth' class='alert-link'>Click here to login</a>.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="shortcut icon" href="/cosmeticsstore/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/bootstrap.css">
    <!-- FontAwesome CSS (offline copy) -->
    <link rel="stylesheet" href="/cosmeticsstore/assets/fontawesome/css/all.min.css">

</head>
<body>
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-12 col-md-6 col-lg-6">
            <div class="login-container shadow-lg p-5">
                <img src="/cosmeticsstore/images/logo.png" alt="Logo" class="logo img-fluid" width="300">
                <h3 class="text-center my-4">Change Password</h3>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($validToken): ?>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password" required minlength="6">
                        </div>

                        <div class="form-group mb-3">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm New Password" required minlength="6">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="showPasswordToggle" onclick="togglePasswordVisibility()">
                            <label class="form-check-label" for="showPasswordToggle">
                                Show Password
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100" id="changePasswordBtn">
                            <span class="btn-text">Change Password</span>
                        </button>

                    </form>

                
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="/cosmeticsstore/assets/js/bootstrap.bundle.min.js"></script>



<!-- JavaScript to toggle visibility -->
<script>
    function togglePasswordVisibility() {
        const newPassword = document.getElementById("new_password");
        const confirmPassword = document.getElementById("confirm_password");
        const type = newPassword.type === "password" ? "text" : "password";
        newPassword.type = type;
        confirmPassword.type = type;
    }

    // Change Password Page
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    if (changePasswordBtn) {
        changePasswordBtn.closest('form').addEventListener('submit', function () {
            changePasswordBtn.disabled = true;
            changePasswordBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        });
    }

</script>


</body>
</html>
