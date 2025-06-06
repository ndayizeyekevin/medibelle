<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
    
    <!-- SweetAlert2 JS -->
    <script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
</head>
<body>
<?php
// process-user.php
session_start();
require '../../includes/conn.php'; // Adjust the path to your connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $id             = trim($_POST['id']);
        $username       = strtolower(trim($_POST['username']));
        $email          = strtolower(trim($_POST['email'])); 
        $role           = trim($_POST['role']);
        $account_status = trim($_POST['account_status']);
        $firstName      = ucwords(strtolower(trim($_POST['firstName'])));
        $lastName       = strtoupper(trim($_POST['lastName']));
        $phone          = trim($_POST['phone']);
        
        // $email = lcfirst($lastName);

        
        // Update query now includes role and account_status
        $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, username = ?, email = ?, phone = ?, role = ?, account_status = ? WHERE userId = ?");
        $result = $stmt->execute([$firstName, $lastName, $username, $email, $phone, $role, $account_status, $id]);
        
        if ($result) {
            echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'User Updated',
                    text: 'The user has been successfully updated.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './';
                });
            </script>";
        } else {
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'There was an error updating the user.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'users.php';
                });
            </script>";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        // Prepare and execute the delete query
        $stmt = $pdo->prepare("DELETE FROM users WHERE userId = ?");
        $result = $stmt->execute([$id]);
        
        if ($result) {
            echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'User Deleted',
                    text: 'The user has been successfully deleted.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './';
                });
            </script>";
        } else {
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: 'There was an error deleting the user.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = './';
                });
            </script>";
        }
    } else {
        header("Location: ./");
        exit();
    }
} else {
    header("Location: ./");
    exit();
}
?>
</body>
</html>
