<?php
session_start();

$config = require __DIR__ . '/../includes/config.php';
$src    = rtrim($config['phpmailer_src'], '/');

// Load PHPMailer
foreach (['Exception.php', 'PHPMailer.php', 'SMTP.php'] as $file) {
    $path = "$src/$file";
    if (!file_exists($path)) {
        throw new RuntimeException("Missing PHPMailer file: $path");
    }
    require_once $path;
}

require_once __DIR__ . '/../includes/email-template.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$companyLocation = $config['location'];
$logoFilePath = $config['logo_url'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate inputs
    $name    = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email   = htmlspecialchars(trim($_POST["email"] ?? ''));
    $subject = htmlspecialchars(trim($_POST["subject"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_message'] = '<div class="alert alert-danger" role="alert">Invalid email address.</div>';
        header("Location: ./");
        exit;
    }

    // Fast response to browser
    $_SESSION['contact_message'] = '<div class="alert alert-success" role="alert">Thank you for contacting us, ' . $name . '. We will get back to you soon.</div>';
    header("Location: ./");
    ignore_user_abort(true);
    header("Connection: close");
    ob_start();
    echo " ";
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();

    try {
        // ========= Email to Site Owner =========
        $bodyToOwner = "
          <h2>New message from: <span style='color: #054866;'>$name</span></h2>
          <p><strong>Name:</strong> $name<br>
             <strong>Email:</strong> $email</p>
          <blockquote style='border-left: 4px solid #ccc; padding-left: 10px;'>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
          </blockquote>
          <p style='margin-top: 20px;'>Received from MediBelle Website.</p>
        ";

        $mailToOwner = new PHPMailer(true);
        $mailToOwner->isSMTP();
        $mailToOwner->Host       = 'smtp.gmail.com';
        $mailToOwner->SMTPAuth   = true;
        $mailToOwner->Username   = $config['email_username'];
        $mailToOwner->Password   = $config['email_password'];
        $mailToOwner->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailToOwner->Port       = 587;

        $mailToOwner->setFrom($config['email_username'], 'MediBelle Website');
        $mailToOwner->addAddress('ndayizeyekevin6@gmail.com', 'MediBelle Admin');
        $mailToOwner->addEmbeddedImage($logoFilePath, 'logo_cid');
        $mailToOwner->isHTML(true);
        $mailToOwner->Subject = $subject;
        $mailToOwner->Body = renderEmailTemplate("New Contact Message", $bodyToOwner);
        $mailToOwner->send();

        // ========= Confirmation Email to User =========
        $bodyToUser = "
          <h2>Hello $name,</h2>
          <p>Thank you for contacting MediBelle. We’ve received your message and will respond shortly.</p>
          <p>If you did not send this, please ignore the email.</p>
          <p>Here is a copy of your message:</p>
          <blockquote style='border-left: 4px solid #ccc; padding-left: 10px;'>" . nl2br($message) . "</blockquote>
          <p style='margin-top: 10px;'>Best regards,<br><strong>MediBelle Team</strong></p>
        ";

        $mailConfirmation = new PHPMailer(true);
        $mailConfirmation->isSMTP();
        $mailConfirmation->Host       = 'smtp.gmail.com';
        $mailConfirmation->SMTPAuth   = true;
        $mailConfirmation->Username   = $config['email_username'];
        $mailConfirmation->Password   = $config['email_password'];
        $mailConfirmation->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailConfirmation->Port       = 587;

        $mailConfirmation->setFrom($config['email_username'], 'MediBelle Website');
        $mailConfirmation->addAddress($email, $name);
        $mailConfirmation->addEmbeddedImage($logoFilePath, 'logo_cid');
        $mailConfirmation->isHTML(true);
        $mailConfirmation->Subject = "Confirmation: Message received";
        $mailConfirmation->Body = renderEmailTemplate("We received your message", $bodyToUser);
        $mailConfirmation->send();

    } catch (Exception $e) {
        error_log("Email send error: " . $e->getMessage());
        echo "<script>console.error('Email error: " . addslashes($e->getMessage()) . "');</script>";
    }

    exit;
}

header("Location: ./");
exit;
?>
