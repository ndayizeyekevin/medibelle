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
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ./logout.php");
    exit();
}
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Handling the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageUploadPath = 'uploads/' . $imageName;

        // Create the uploads directory if it doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($imageTmpPath, $imageUploadPath)) {
            try {

                $stmt = $pdo->prepare("INSERT INTO products (name, price, image) VALUES (:name, :price, :image)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':image', $imageUploadPath);

                $stmt->execute();
                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Saved',
                            text: `The cart has been successfully saved!
                                Our Agent will contact you soon!`,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = './index.php'; // Redirect to the cart page
                        });
                    </script>";
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Failed to upload the image.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Please upload a valid image file.</div>";
    }
}
?>
</body>
</html>