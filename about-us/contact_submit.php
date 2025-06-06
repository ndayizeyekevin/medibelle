<?php
session_start();

// Use Composer autoloading if available; otherwise, load PHPMailer manually.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    require_once 'C:/xampp/htdocs/phpmailer/src/Exception.php';
    require_once 'C:/xampp/htdocs/phpmailer/src/PHPMailer.php';
    require_once 'C:/xampp/htdocs/phpmailer/src/SMTP.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require '../contact/config.php'; // Load configuration

// Set your company location (adjust as needed)
$companyLocation = "KK 17 Avenue, Kigali, Rwanda";

// Filesystem path for the logo image (adjust as needed)
$logoFilePath = 'C:/xampp/htdocs/cosmeticsstore/images/logo.jpg';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = htmlspecialchars(trim($_POST["email"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_message'] = '<div class="alert alert-danger" role="alert">Invalid email address.</div>';
        header("Location: ./");
        exit;
    }
    
    // Set the success message and redirect immediately.
    $_SESSION['contact_message'] = '<div class="alert alert-success" role="alert">
                Thank you for contacting us, ' . $name . '. We will get back to you soon.
            </div>';
    header("Location: ./");
    
    // ----- Force immediate response to client -----
    ignore_user_abort(true);         // Continue processing even if the client disconnects.
    header("Connection: close");     // Tell the client the connection is closed.
    ob_start();
    echo " ";                        // Output a minimal response.
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();
    // -------------------------------------------------
    
    // Process emails in the background.
    try {
        // -------------------------------------
        // Send email to the site owner/company
        // -------------------------------------
        $mailToOwner = new PHPMailer(true);
        $mailToOwner->isSMTP();
        $mailToOwner->Host       = 'smtp.gmail.com';
        $mailToOwner->SMTPAuth   = true;
        $mailToOwner->Username   = $config['email_username'];
        $mailToOwner->Password   = $config['email_password'];
        $mailToOwner->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailToOwner->Port       = 587;

        // Set sender and recipient for the owner’s email
        $mailToOwner->setFrom($config['email_username'], 'Sabans Pharmacy E-Commerce Website');
        $mailToOwner->addAddress('ndayizeyekevin6@gmail.com', 'Sabans Pharmacy');

        // Embed the logo image into the email
        $mailToOwner->addEmbeddedImage($logoFilePath, 'logo_cid');

        // Build the HTML content for the owner’s email using the embedded image
        $mailToOwner->isHTML(true);
        $mailToOwner->Subject = $subject;
        $mailToOwner->Body = "
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset='UTF-8'>
          <title>New Contact Message</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; margin: 20px;'>
          <img src='cid:logo_cid' width='80px' alt='Logo'>
          <h2>New message from: <span style='color: #054866;'>$name</span></h2>
          <h4>Name: <span style='color: #054866;'>$name</span></h4>
          <h4>Email: <span style='color: #054866;'>$email</span></h4>
          <blockquote style='border-left: 7px solid #ccc; margin-left: 0; padding-left: 1em; color: #054866;'>
            <p style='font-size: 1.2em; font-weight: bold;'>Message</p>
            " . nl2br($message) . "
          </blockquote>
          <div style='border-top: 4px solid #054866; margin-top: 20px; padding-top: 10px;'>
            <p style='color: #054866; font-weight: bold;'>Received from Sabans Pharmacy E-Commerce Website.</p>
          </div>
          <div style='margin-top: 10px; color: #054866'>
            <h2>Contact Support</h2>
            <h4>Email: ndayizeye.kevin@outlook.com</h4>
            <h4>Phone: 0786 591 604</h4>
            <h4>Location: $companyLocation</h4>
          </div>
        </body>
        </html>
        ";
        $mailToOwner->send();

        // ------------------------------------------------------
        // Send confirmation email to the user (form submitter)
        // ------------------------------------------------------
        $mailConfirmation = new PHPMailer(true);
        $mailConfirmation->isSMTP();
        $mailConfirmation->Host       = 'smtp.gmail.com';
        $mailConfirmation->SMTPAuth   = true;
        $mailConfirmation->Username   = $config['email_username'];
        $mailConfirmation->Password   = $config['email_password'];
        $mailConfirmation->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailConfirmation->Port       = 587;

        // Set sender and recipient for the confirmation email
        $mailConfirmation->setFrom($config['email_username'], 'Sabans Pharmacy E-Commerce Website');
        $mailConfirmation->addAddress($email, $name);

        // Embed the logo image for the confirmation email
        $mailConfirmation->addEmbeddedImage($logoFilePath, 'logo_cid_conf');

        $mailConfirmation->isHTML(true);
        $mailConfirmation->Subject = "Confirmation: Your message has been received";
        $mailConfirmation->Body = "
          <!DOCTYPE html>
          <html>
          <head>
            <meta charset='UTF-8'>
            <title>Confirmation Email</title>
          </head>
          <body style='font-family: Arial, sans-serif; line-height: 1.6; margin: 20px;'>
            <img src='cid:logo_cid_conf' width='80px' alt='Logo'>
            <h2>Hello $name,</h2>
            <p style='font-size: 1.3em; color: #000;'>Thank you for contacting Sabans Pharmacy. We have received your message and will get back to you shortly.</p>
            <p style='font-size: 1em; color: #000;'>If you did not fill out our form or this email was not intended for you, please ignore this message.</p>
            <p style='font-size: 1em; color: #000;'>Here is a copy of your message for your records:</p>
            <div style='border-bottom: 3px solid rgb(18, 80, 62);'>
              <blockquote style='border-left: 7px solid rgb(5, 51, 51); margin-left: 0; padding-left: 1em; color: rgb(18, 80, 62);'>
                " . nl2br($message) . "
              </blockquote>
            </div>
            <div style='margin-top: 10px; padding-top: 10px; color: rgb(8, 119, 119);'>
              <h3 style='font-size: 1.1em;'>Sabans Pharmacy</h3>
              <h3 style='font-size: 1.1em;'>Phone: <a href='tel:+250781474454'>(+250) 786 591 604</a></h3>
              <h3 style='font-size: 1.1em;'>Email: ndayizeye.kevin@outlook.com</h3>
              <h3 style='font-size: 1.1em;'>Location: $companyLocation</h3>
              <hr>
              <p style='color: #000;'>Best regards,</p>
              <h3 style='font-size: 1.1em; color: #000;'><strong>Sabans Pharmacy Team</strong></h3>
            </div>
          </body>
          </html>
        ";
        try {
            $mailConfirmation->send();
        } catch (Exception $e) {
            error_log("Confirmation email failed: " . $mailConfirmation->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
    }
    exit;
} else {
    header("Location: ./");
    exit;
}
?>
