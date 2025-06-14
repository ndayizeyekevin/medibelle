<?php
// upload-handler.php

session_start();
$config = require __DIR__ . '/config.php';
$src    = rtrim($config['phpmailer_src'], '/');

// Ensure the three required files exist before including
foreach (['Exception.php','PHPMailer.php','SMTP.php'] as $file) {
    $path = "$src/$file";
    if (! file_exists($path) ) {
        throw new RuntimeException("Missing PHPMailer file: $path");
    }
    require_once $path;
}
require __DIR__ . '/connection.php';        
require __DIR__ . '/email-template.php';    


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prescription Upload</title>
  <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
</head>
<body style="background:#f0f0f0;">

<script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>

<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
      Swal.fire('Invalid Request','This page only accepts POST.','error')
           .then(()=> history.back());
    </script>";
    exit;
}

// 1) Sanitize inputs
$fullName = $dsn->real_escape_string(trim($_POST['fullName']));
$email    = $dsn->real_escape_string(trim($_POST['email']));
$phone    = $dsn->real_escape_string(trim($_POST['phoneNumber']));
$address  = $dsn->real_escape_string(trim($_POST['address']));
$date     = date('Y-m-d');
$status   = 'pending';

// 2) Validate & move file
if (!isset($_FILES['prescription']) || $_FILES['prescription']['error'] !== UPLOAD_ERR_OK) {
    echo "<script>
      Swal.fire('Upload Error','No file uploaded or an error occurred.','error')
           .then(()=> history.back());
    </script>";
    exit;
}

$file     = $_FILES['prescription'];
$size     = $file['size'];
$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed  = ['jpg','jpeg','png','pdf'];

if ($size > 3 * 1024 * 1024) {
    echo "<script>
      Swal.fire('File Too Large','Your file exceeds 3MB.','warning')
           .then(()=> history.back());
    </script>";
    exit;
}

if (!in_array($ext, $allowed, true)) {
    echo "<script>
      Swal.fire('Invalid File Type','Only JPG, PNG & PDF allowed.','error')
           .then(()=> history.back());
    </script>";
    exit;
}

$targetDir = __DIR__ . '/../prescriptions/';
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
// Website Address
$website    = $config['officialWebsite'];
$cd   = '..';
$path = '/prescriptions/' . time() . '_presc_' . uniqid() . '.' . $ext;
$basename   = $cd . $path;
$filePath   = $website . $path;
$targetPath = $targetDir . $basename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo "<script>
      Swal.fire('Upload Failed','Could not move uploaded file.','error')
           .then(()=> history.back());
    </script>";
    exit;
}

// 3) Begin MySQL transaction
$dsn->autocommit(false);
$dsn->begin_transaction();

try {
    // 4) Insert record
    $stmt = $dsn->prepare("
      INSERT INTO prescriptions
        (attachment, fullName, email, phone, address, date, status)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $dsn->error);
    }
    $stmt->bind_param(
      'sssssss',
      $basename,
      $fullName,
      $email,
      $phone,
      $address,
      $date,
      $status
    );
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    $stmt->close();

    // 5) Prepare email bodies
    $ownerBody = "
      <h2>New Prescription Uploaded</h2>
      <p><strong>Name:</strong> {$fullName}<br>
         <strong>Email:</strong> {$email}<br>
         <strong>Phone:</strong> {$phone}<br>
         <strong>Address:</strong> {$address}<br>
         <strong>Date:</strong> {$date}</p>
      <p>File Path: <code>{$filePath}</code></p>
    ";
    $userBody = "
      <h2>Thank you, {$fullName}!</h2>
      <p>We have received your prescription and will process it shortly.</p>
      <p><strong>Your reference:</strong> {$basename}</p>
    ";

    // 6) Configure PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['email_username'];
    $mail->Password   = $config['email_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom($config['email_username'], 'Cosmetics Store');
    $mail->isHTML(true);

    // 6a) Send to pharmacy owner
    $mail->addAddress($config['support_email']);
    $mail->Subject = "New Prescription Uploaded";
    $mail->addEmbeddedImage($config['logo_url'], 'logo_cid');
    $mail->Body    = renderEmailTemplate('New Prescription', $ownerBody);
    $mail->send();
    $mail->clearAddresses();

    // 6b) Send confirmation to user
    $mail->addAddress($email);
    $mail->Subject = "Your Prescription Upload Confirmation";
    $mail->addEmbeddedImage($config['logo_url'], 'logo_cid');
    $mail->Body    = renderEmailTemplate('Upload Received', $userBody);
    $mail->send();

    // 7) Commit transaction
    $dsn->commit();

    echo "<script>
      Swal.fire('Success','Prescription saved & emails sent.','success')
           .then(()=> window.location.href = '../prescription');
    </script>";

} catch (Exception $e) {
    // Roll back on any error
    $dsn->rollback();
    // Remove the uploaded file if desired
    if (file_exists($targetPath)) unlink($targetPath);
    error_log('Upload-handler error: ' . $e->getMessage());

    echo "<script>
      Swal.fire('Error','An error occurred: {$e->getMessage()}','error')
           .then(()=> history.back());
    </script>";
}

// 8) Restore autocommit
$dsn->autocommit(true);
?>

</body>
</html>
