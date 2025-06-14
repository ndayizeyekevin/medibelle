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
                    window.location.href = '../../auth';
                });
            </script>
";
    // header("Location: ../../auth");
    exit();
}
require '../../includes/conn.php'; // adjust the path as needed
// Title
$title = 'Users Management';
?>

  <?php include '../includes/header.php'; // your site header ?>

  <style>
    /* Optional custom styles */
    .form-container {
      max-width: 600px;
      margin: auto;
    }

    .side-bar1 {
      color: #0000ff;
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
  
    /* Optional: Adjust table styling as needed */
    table th, table td {
      vertical-align: middle;
    }
  </style>

    <div class="mx-3">
      <h2 class="mb-4">Users Management</h2>
      

      <!-- Users Table -->
       <div class="table-responsive container">
          <table id="usersTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Fetch users from the database
              $stmt = $pdo->prepare("SELECT * FROM users WHERE userId != ?");
              $stmt->execute([$_SESSION['userId']]);
              // Declaring counter
              $counter = 1;
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  // Convert account_status to text
                  $statusText = ($row['account_status'] == 1) ? "Active" : "Inactive";
                  echo "<tr>";
                  echo "<td>{$counter}</td>";
                  echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                  echo "<td>" . $statusText . "</td>";
                  echo "<td>
                          <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['userId']}'>
                            <i class='fas fa-edit'></i> Edit
                          </button>
                          <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['userId']}'>
                            <i class='fas fa-trash'></i> Delete
                          </button>
                        </td>";
                  echo "</tr>";

                  // Edit Modal for user with id = {$row['userId']}
                  echo "<div class='modal fade' id='editModal{$row['userId']}' tabindex='-1' aria-hidden='true'>
                          <div class='modal-dialog'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                <h5 class='modal-title'>Edit User: {$row['firstName']}</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                              </div>
                              <form action='./process-user.php' method='POST'>
                                <div class='modal-body'>
                                  <input type='hidden' name='id' value='{$row['userId']}'>
                                  <div class='mb-3'>
                                    <label class='form-label'>First Name</label>
                                    <input type='text' class='form-control' name='firstName' value='" . htmlspecialchars($row['firstName']) . "' required>
                                  </div>
                                  <div class='mb-3'>
                                    <label class='form-label'>Last Name</label>
                                    <input type='text' class='form-control' name='lastName' value='" . htmlspecialchars($row['lastName']) . "' required>
                                  </div>
                                  <div class='mb-3'>
                                    <label class='form-label'>Username</label>
                                    <input type='text' class='form-control' name='username' value='" . htmlspecialchars($row['username']) . "' required>
                                  </div>
                                  ";
                                  ?>
                                  <div class='mb-3'>
                                    <label class='form-label'>Phone Number</label>
                                    <input type='text' class='form-control' name='phone' value='<?=$row['phone']; ?>' placeholder="Example: 0788123456" minlength="10" maxlength="10"
                                    pattern="^(078|079|072|073)\d{7}$"
                                    inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    maxlength="10"
                                    title="Enter a 10-digit number starting with 078, 079, 072, or 073" required>
                                  </div>
                                  <?php
                                  echo "
                                  <div class='mb-3'>
                                    <label class='form-label'>Email</label>
                                    <input type='email' class='form-control' name='email' value='" . htmlspecialchars($row['email']) . "' required>
                                  </div>
                                  <div class='mb-3'>
                                    <label class='form-label'>Role</label>
                                    <select class='form-select' name='role' required>
                                      <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                                      <option value='custom' " . ($row['role'] == 'custom' ? 'selected' : '') . ">Custom</option>
                                    </select>
                                  </div>
                                  <div class='mb-3'>
                                    <label class='form-label'>Account Status</label>
                                    <select class='form-select' name='account_status' required>
                                      <option value='1' " . ($row['account_status'] == 1 ? 'selected' : '') . ">Active</option>
                                      <option value='0' " . ($row['account_status'] == 0 ? 'selected' : '') . ">Inactive</option>
                                    </select>
                                  </div>
                                </div>
                                <div class='modal-footer'>
                                  <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                  <button type='submit' name='update' class='btn btn-primary'>Save Changes</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>";

                  // Delete Confirmation Modal for user with id = {$row['userId']}
                  echo "<div class='modal fade' id='deleteModal{$row['userId']}' tabindex='-1' aria-hidden='true'>
                          <div class='modal-dialog'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                <h5 class='modal-title'>Confirm Delete</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                              </div>
                              <div class='modal-body'>
                                Are you sure you want to delete this user?
                              </div>
                              <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                <form action='./process-user.php' method='POST' style='display:inline;'>
                                  <input type='hidden' name='id' value='{$row['userId']}'>
                                  <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>";
                // Incrementing the counter
                $counter ++;
              }
              ?>
            </tbody>
          </table>
       </div>
      
    </div>
  </div>

  <?php include '../includes/footer.php'; // your site footer ?>


  <script>
    $(document).ready(function(){
      // Initialize DataTable with default settings
      $('#usersTable').DataTable({
        lengthMenu: [5, 10, 25, 50, 100],
        pageLength: 10,
        ordering: true,
        language: {
          search: "Search Users:"
        }
      });
    });
  </script>
</body>
</html>
