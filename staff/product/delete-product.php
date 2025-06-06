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
<body>
<?php

require '../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve and sanitize input
    $id          = $_GET['id'];
   

    // Proceed with DELETE
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Product Deleted',
                    text: 'The product has been DELETEd successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './index.php';
                });
              </script>";
    } else {
        
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: 'There was an error updating the product. Please try again.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './index.php';
                });
              </script>";
    }
} else {
    echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid request',
                    text: 'There was an error deleting the product. Please try again.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './index.php';
                });
              </script>";
}
?>

</body>
</html>