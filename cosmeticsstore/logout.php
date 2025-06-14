<?php
// logout.php
session_start();     // Start the session
session_unset();     // Unset all session variables
session_destroy();   // Destroy the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout</title>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <script>
    // Display a SweetAlert2 message, then redirect when the user confirms.
    // Swal.fire({
    //   title: 'Logged Out',
    //   text: 'You have been successfully logged out. Click "Login" to access your account again.',
    //   icon: 'success',
    //   confirmButtonText: 'Login'
    // }).then((result) => {
    //   // Redirect to the login page when the user confirms.
    //   window.location.href = "./login.php";
    // });
    window.location.href = "./auth";
  </script>
</body>
</html>
