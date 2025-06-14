<?php
// profile.php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../auth");
    exit();
}

require '../../includes/conn.php'; // adjust the path as needed
$title = "Profile";
$message = "";
$messageClass = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim inputs
    $oldPassword     = trim($_POST['old_password'] ?? '');
    $newPassword     = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    
    // Basic validation checks
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $message = "All fields are required.";
        $messageClass = "alert-danger";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "New password and confirmation do not match.";
        $messageClass = "alert-danger";
    } elseif (strlen($newPassword) < 8) {
        $message = "New password must be at least 8 characters long.";
        $messageClass = "alert-danger";
    } elseif ($oldPassword === $newPassword) {
        $message = "New password cannot be the same as the old password.";
        $messageClass = "alert-danger";
    } else {
        $userId = $_SESSION['userId'];
        // Fetch the current hashed password from the database
        $stmt = $pdo->prepare("SELECT password FROM users WHERE userId = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $currentHashed = $user['password'];
            if (password_verify($oldPassword, $currentHashed)) {
                // Hash the new password and update the database
                $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE userId = ?");
                if ($updateStmt->execute([$newHashed, $userId])) {
                    $message = "Password updated successfully!";
                    $messageClass = "alert-success";
                } else {
                    $message = "Failed to update password. Please try again.";
                    $messageClass = "alert-danger";
                }
            } else {
                $message = "Old password is incorrect.";
                $messageClass = "alert-danger";
            }
        } else {
            $message = "User not found.";
            $messageClass = "alert-danger";
        }
    }
}
?>

  <?php include '../includes/header.php'; // your site header ?>

  

    <div class="container">
      <h2 class='container mt-4'>Change Password</h2>
      <div class="container mt-5">
        <?php if (!empty($message)): ?>
          <div class="alert <?php echo $messageClass; ?>">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
          <div class="mb-3">
            <label for="old_password" class="form-label">Old Password</label>
            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" required>
          </div>
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" min-length="8" name="new_password" placeholder="New Password" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" min-length="8" name="confirm_password" placeholder="Confirm New Password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-save"></i> Save Changes
          </button>
        </form>
      </div>
    </div>
  </div>

  <?php include '../includes/footer.php'; // your site footer ?>

 
</body>
</html>
