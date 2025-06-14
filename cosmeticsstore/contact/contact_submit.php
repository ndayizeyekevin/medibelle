<?php
session_start();

$config = require __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/email-template.php';
require_once __DIR__ . '/../includes/send-email.php';

use PHPMailer\PHPMailer\Exception;

$companyLocation = $config['location'];
$logoFilePath    = $config['logo_url'];

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

    // Set session message for user feedback (fast browser response)
    $_SESSION['contact_message'] = '<div class="alert alert-success" role="alert">Thank you for contacting us, ' . $name . '. We will get back to you soon.</div>';
    header("Location: ./");

    // Send response before processing emails
    ignore_user_abort(true);
    header("Connection: close");
    ob_start();
    echo " ";
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();

    // Email to site owner
    $bodyToOwner = "
        <h2>New message from: <span style='color: #054866;'>$name</span></h2>
        <p><strong>Name:</strong> $name<br>
           <strong>Email:</strong> $email</p>
        <blockquote style='border-left: 4px solid #ccc; padding-left: 10px;'>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        </blockquote>
        <p style='margin-top: 20px;'>Received from MediBelle Website.</p>
    ";

    $sentOwner = sendEmail(
        'ndayizeyekevin6@gmail.com',
        'MediBelle Customer Service',
        $subject,
        $bodyToOwner,
        $config,
        $logoFilePath
    );

    // Confirmation email to user
    $bodyToUser = "
        <h2>Hello $name,</h2>
        <p>Thank you for contacting MediBelle. We’ve received your message and will respond shortly.</p>
        <p>If you did not send this, please ignore the email.</p>
        <p>Here is a copy of your message:</p>
        <blockquote style='border-left: 4px solid #ccc; padding-left: 10px;'>" . nl2br($message) . "</blockquote>
        <p style='margin-top: 10px;'>Best regards,<br><strong>{$config['supportTeam']}</strong></p>
    ";

    $sentUser = sendEmail(
        $email,
        $name,
        "Confirmation: Message received",
        $bodyToUser,
        $config,
        $logoFilePath
    );

    if (!$sentOwner || !$sentUser) {
        error_log("One or both emails failed to send.");
    }

    exit;
}

// Fallback redirect
header("Location: ./");
exit;
