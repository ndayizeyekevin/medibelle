<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure PHPMailer is loaded
$src = rtrim($config['phpmailer_src'], '/');
foreach (['Exception.php', 'PHPMailer.php', 'SMTP.php'] as $file) {
    $path = "$src/$file";
    if (!file_exists($path)) {
        throw new RuntimeException("Missing PHPMailer file: $path");
    }
    require_once $path;
}

function sendEmail($to, $toName, $subject, $bodyHTML, $config, $logoFilePath) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['email_username'];
        $mail->Password   = $config['email_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($config['email_username'], 'MediBelle');
        $mail->addAddress($to, $toName);
        $mail->addEmbeddedImage($logoFilePath, 'logo_cid');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = renderEmailTemplate($subject, $bodyHTML);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}
