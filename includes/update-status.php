<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
  
    <!-- SweetAlert2 JS -->
    <script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
</head>
<body bg-color='gray'>
<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../logout.php");
    exit();
}
require './connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['prescriptionId']) && isset($_POST['status'])) {
    $prescriptionId = intval($_POST['prescriptionId']);
    $newStatus = $_POST['status'];

    // Validate status value
    $validStatuses = ['pending', 'verified', 'rejected'];
    if (!in_array($newStatus, $validStatuses)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Status',
                    text: '❌ The selected status is not valid.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
              </script>";
        exit();
    }

    // Update status in the database
    $stmt = $dsn->prepare("UPDATE prescriptions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $prescriptionId);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    text: `✅ Prescription status updated successfully!`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '../admin/orders/prescription'; // Redirect after update
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: '❌ Failed to update prescription status.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
              </script>";
    }
    
    $stmt->close();
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Request',
                text: '❌ Missing required data.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.history.back();
            });
          </script>";
}
?>
</body>
</html>