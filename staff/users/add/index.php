<html>
  <head>
    
<link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
  
<!-- SweetAlert2 JS -->
<script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
  </head>
  <body>
<?php
// users.php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'admin') {
  echo "
      
      <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Unauthorized',
                    text: 'Login As Admin to Access this page.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '../../../auth';
                });
            </script>
";
    // header("Location: ../../auth");
    exit();
}
// Title
$title = 'Create User';
require '../../../includes/conn.php'; // adjust the path as needed

$message = "";
$messageClass = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim form inputs
    $firstName       = trim($_POST['firstName'] ?? '');
    $lastName        = trim($_POST['lastName'] ?? '');
    $username        = trim($_POST['username'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $accountStatus   = trim($_POST['account_status'] ?? '');
    $role            = trim($_POST['role'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');

    // Validate inputs - all fields are required
    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($phone) || $accountStatus === '' || empty($role)) {
        $message = "All fields are required.";
        $messageClass = "alert-danger";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
        $messageClass = "alert-danger";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
        $messageClass = "alert-danger";
    } else {
        // Check if a user with the same username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            $message = "A user with that username or email already exists.";
            $messageClass = "alert-danger";
        } else {
            // Hash the password and insert the new user including created_at timestamp using NOW()
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, username, email, password, phone, account_status, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            if ($stmt->execute([$firstName, $lastName, $username, $email, $hashedPassword, $phone, $accountStatus, $role])) {
                $message = "User created successfully!";
                $messageClass = "alert-success";
            } else {
                $message = "Failed to create user.";
                $messageClass = "alert-danger";
            }
        }
    }
}
?>


  <?php include '../../includes/header.php'; // your site header ?>
  <style>
    /* Optional custom styles */
    .form-container {
      max-width: 600px;
      margin: auto;
    }

    .side-bar1 {
      color: #00f;
    }
    .side-bar1 a:hover {
      color: #000;
      padding: 5px;
    }

    .side-bar1 ul li {
      list-style: none;
      font-size: 20px;
      display: inline;
      padding: 5px;
    }
    /* Style for per-column search inputs */
    tfoot input {
      width: 100%;
      padding: 3px;
      box-sizing: border-box;
    }
  </style>

    <div class="container mt-3">
      <?php if (!empty($message)): ?>
        <div class="alert <?php echo $messageClass; ?>">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>
      <h3 class='mb-3'>Create User</h3>
      
      <div class="container mb-5">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class='staff-form' method="POST">
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="firstName" class="form-label">First Name</label>
              <input type="text" class="form-control" minlength="3" placeholder="Enter First Name" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="lastName" class="form-label">Last Name</label>
              <input type="text" class="form-control" minlength="3" placeholder="Enter Last Name" id="lastName" name="lastName" required>
            </div>
          </div>
          

          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="username" class="form-label">Username</label>
              <input type="text" minlength="4" class="form-control" placeholder="Enter Username" id="username" name="username" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="Enter Email" id="email" minlength="10" name="email" required>
            </div>
          </div>

          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="tel"class="form-control" id="phone" name="phone"
                  placeholder="Example: 0788123456" minlength="10" maxlength="10"
                  pattern="^(078|079|072|073)\d{7}$"
                  inputmode="numeric"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                  maxlength="10"
                  title="Enter a 10-digit number starting with 078, 079, 072, or 073" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="password" class="form-label">Password (min 8 characters)</label>
              <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required>
            </div>
          </div>

          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="account_status" class="form-label">Account Status</label>
              <select name="account_status" id="account_status" class="form-select" required>
                <option value="">Select Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="role" class="form-label">Role</label>
              <select name="role" id="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="custom">Custom</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Create User
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

  <?php include '../../includes/footer.php'; // your site footer ?>

  
</body>
</html>
