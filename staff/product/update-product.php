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
require '../../includes/conn.php'; // Your PDO connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $id          = $_POST['id'];
    $name        = trim($_POST['name']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);

    // Validate that no field is empty
    if (empty($name) || empty($category) || empty($description) || empty($price)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'All fields are required. Please fill in all fields.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './index.php';
                });
              </script>";
        exit;
    }

    // Proceed with update
    $sql = "UPDATE products SET name = :name, category = :category, description = :description, price = :price WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Product Updated',
                    text: 'The product has been updated successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './index.php';
                });
              </script>";
    } else {
        
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'There was an error updating the product. Please try again.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './index.php';
                });
              </script>";
    }
} else {
    header('Location: ./index.php');
    exit;
}
?>

</body>
</html>