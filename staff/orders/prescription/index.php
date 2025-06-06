<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../../auth");
    exit();
}
require '../../../includes/connection.php'; // Include database connection
// Title
$title = 'Prescription Orders';
?>

  <?php include '../../includes/header.php'; ?>


    <div class="mx-4 mt-2">
        <h2 class="mb-4">Prescription Records</h2>
        <div class="table-responsive">
          <table id="prescriptionTable" class="table table-bordered table-striped">
              <thead class="table-dark">
                  <tr>
                      <th>ID</th>
                      <th>Full Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $sql = "SELECT * FROM prescriptions WHERE status='pending' ORDER BY date DESC";
                  $result = $dsn->query($sql);
                  $counter = 1;
                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . $counter . "</td>";
                          $counter++;
                          echo "<td>" . htmlspecialchars($row['fullName']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                          echo "<td>" . $row['date'] . "</td>";
                          echo "<td class='status-text'>" . ucfirst($row['status']) . "</td>";
                          echo "<td>
                              <button class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#viewModal{$row['id']}'><i class='fas fa-eye'></i> View</button>
                              <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'><i class='fas fa-edit'></i> Edit Status</button>
                          </td>";
                          echo "</tr>";

                          // View Prescription Modal
                          echo "<div class='modal fade' id='viewModal{$row['id']}' tabindex='-1' aria-labelledby='modalLabel' aria-hidden='true'>";
                          echo "<div class='modal-dialog'>";
                          echo "<div class='modal-content'>";
                          echo "<div class='modal-header'>";
                          echo "<h5 class='modal-title'>Prescription Details</h5>";
                          echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                          echo "</div>";
                          echo "<div class='modal-body'>";
                          echo "<p class='text-start mx-3'><strong>Full Name:</strong> " . htmlspecialchars($row['fullName']) . "</p>";
                          echo "<p class='text-start mx-3'><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
                          echo "<p class='text-start mx-3'><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                          echo "<p class='text-start mx-3'><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                          echo "<p class='text-start mx-3'><strong>Date:</strong> " . $row['date'] . "</p>";
                          echo "<p class='text-start mx-3'><strong>Status:</strong> " . ucfirst($row['status']) . "</p>";
                          echo "<p class='text-start mx-3'><strong>Attachment:</strong> <a href='includes/../../../" . $row['attachment'] . "' target='_blank'>View File</a></p>";
                          echo "</div>";
                          echo "</div>";
                          echo "</div>";
                          echo "</div>";

                          // Edit Status Modal
                          echo "<div class='modal fade' id='editModal{$row['id']}' tabindex='-1' aria-hidden='true'>";
                          echo "<div class='modal-dialog'>";
                          echo "<div class='modal-content'>";
                          echo "<div class='modal-header'>";
                          echo "<h5 class='modal-title'>Edit Prescription Status</h5>";
                          echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                          echo "</div>";
                          echo "<div class='modal-body'>";
                          echo "<form action='../../../includes/update-status.php' method='POST'>";
                          echo "<input type='hidden' name='prescriptionId' value='" . $row['id'] . "'>";
                          echo "<label for='status'>Select Status:</label>";
                          echo "<select name='status' class='form-control'>";
                          echo "<option value='pending'>Pending</option>";
                          echo "<option value='verified'>Verify</option>";
                          echo "<option value='canceled'>Cancel</option>";
                          echo "</select><br>";
                          echo "<button type='submit' class='btn btn-success'>Update</button>";
                          echo "</form>";
                          echo "</div>";
                          echo "</div>";
                          echo "</div>";
                          echo "</div>";
                      }
                  } else {
                      echo "<tr><td colspan='7' class='text-center'>No prescriptions found.</td></tr>";
                  }
                  ?>
              </tbody>
          </table>
        </div>
    </div>
</div>
<!-- Footer -->
<?php include '../../includes/footer.php'; ?>
    <script>
        $(document).ready(function () {
            $('#prescriptionTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5
            });
        });
    </script>
</body>
</html>
