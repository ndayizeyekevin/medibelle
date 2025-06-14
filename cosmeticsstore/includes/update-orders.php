<?php
// update_order_status.php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../logout.php");
    exit();
}
include './connection.php'; // Adjust the path and ensure $conn is your mysqli connection
$conn =$dsn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId    = $_POST['order_id'];
    $newStatus  = $_POST['order_status'];

    if (empty($orderId) || empty($newStatus)) {
        $title = "Error";
        $message = "Missing order ID or status.";
        $icon = "error";
        $redirect = "orders.php";
    } else {
        // Use a prepared statement to update order status
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE o_id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);

        if ($stmt->execute()) {
            $title = "Success";
            $message = "Order status updated successfully.";
            $icon = "success";
            $redirect = "../admin/orders";
        } else {
            error_log("Error updating order status: " . $stmt->error);
            $title = "Error";
            $message = "Failed to update order status.";
            $icon = "error";
            $redirect = "../admin/orders";
        }
        $stmt->close();
    }
} else {
    $title = "Error";
    $message = "Invalid request method.";
    $icon = "error";
    $redirect = "../orders.php";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Updating Order Status</title>
  <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
  <!-- SweetAlert2 JS -->
  <script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
</head>
<body>
  <script>
    Swal.fire({
      title: "<?php echo $title; ?>",
      text: "<?php echo $message; ?>",
      icon: "<?php echo $icon; ?>",
      confirmButtonText: "OK"
    }).then(() => {
      window.location.href = "<?php echo $redirect; ?>";
    });
  </script>
</body>
</html>
