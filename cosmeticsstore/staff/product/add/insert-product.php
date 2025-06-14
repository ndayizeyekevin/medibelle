<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../auth");
    exit();
}

require '../../../includes/conn.php'; // Your PDO connection file

// A helper function to output an HTML page with a SweetAlert message and a redirect.
function sweetAlertRedirect($title, $text, $icon, $redirectUrl) {
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Redirecting...</title>
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
  
</head>
<body>
    <!-- SweetAlert2 JS -->
    <script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
  <script>
    Swal.fire({
      title: "{$title}",
      text: "{$text}",
      icon: "{$icon}",
      confirmButtonText: "OK"
    }).then(() => {
      window.location.href = "{$redirectUrl}";
    });
  </script>
</body>
</html>
HTML;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $name        = trim($_POST['name']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);

    // Validate required fields
    if (empty($name) || empty($category) || empty($description) || empty($price)) {
        sweetAlertRedirect("Error", "Please fill in all required fields.", "error", "./");
    }

    // Initialize image path variable (null if no image provided)
    $imagePath = null;

    // Process file upload if an image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Define allowed file types and max file size (2MB)
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileName    = $_FILES['image']['name'];
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileSize    = $_FILES['image']['size'];
        $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowed)) {
            sweetAlertRedirect("Error", "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.", "error", "./");
        }

        if ($fileSize > 2 * 1024 * 1024) { // 2MB in bytes
            sweetAlertRedirect("Error", "File size exceeds 2MB limit.", "error", "./");
        }

        // Create a unique file name
        $newFileName = uniqid("product_", true) . '.' . $fileExt;
        $uploadDir   = '../../../uploads/'; // Adjust the directory as needed

        // Create the directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destPath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Save the relative path to the database
            $imagePath = "uploads/" . $newFileName;
        } else {
            sweetAlertRedirect("Error", "There was an error uploading the file.", "error", "./");
        }
    }

    // Insert the new product into the database using a prepared statement
    try {
        $sql = "INSERT INTO products (name, category, description, price, image)
                VALUES (:name, :category, :description, :price, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $imagePath);
        $stmt->execute();

        sweetAlertRedirect("Success", "Product added successfully.", "success", "./");
    } catch (PDOException $e) {
        error_log("Insert product error: " . $e->getMessage());
        sweetAlertRedirect("Error", "There was an error inserting the product into the database.", "error", "./");
    }
} else {
    // If the request is not a POST, redirect back to the add product page
    sweetAlertRedirect("Error", "Invalid request method.", "error", "./");
}
?>