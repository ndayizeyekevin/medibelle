<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../logout.php");
    exit();
}
$title = "Reports Management";
?>

  <!-- Sidebar -->
  <?php include '../includes/header.php'; ?>

 
    <!-- Orders Menu -->
    <div class="container mt-3">
      <h2>Generate Reports by Status and Date Range</h2>
      <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <form action="./export-orders.php" target="_blank" method="post" class="border p-4 rounded">
              <div class="mb-3">
                <label for="table" class="form-label">Select Table:</label>
                <select id="table" name="table" class="form-select bg-primary text-light" required>
                  <option value="">Select Table</option>
                  <option value="prescriptions">Prescription Orders</option>
                  <option value="orders">Regular Orders</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Order Status:</label>
                <select id="status" name="status" class="form-select bg-primary text-light" required>
                  <option value="">Select Status</option>
                  <!-- Options will be populated based on table selection -->
                </select>
              </div>

              <div class="mb-3">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="format" class="form-label">Export Format:</label>
                <select id="format" name="format" class="form-select bg-primary text-light" required>
                  <option value="">Select File Format</option>
                  <option value="pdf">PDF</option>
                  <option value="excel">Excel</option>
                </select>
              </div>

              <button id="exportButton" type="submit" class="btn btn-primary w-100 bg-dark">
                <i class="fa fa-eye"></i> View Report
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>
  
  
  <script>
    // Update status options based on selected table
    document.getElementById('table').addEventListener('change', function() {
      var table = this.value;
      var statusSelect = document.getElementById('status');
      statusSelect.innerHTML = '';

      if (table === 'prescriptions') {
        var statuses = ['pending', 'verified', 'rejected'];
      } else if (table === 'orders') {
        var statuses = ['pending', 'verified', 'canceled'];
      }

      statuses.forEach(function(status) {
        var option = document.createElement('option');
        option.value = status;
        option.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        statusSelect.appendChild(option);
      });
    });

    // Update button text and icon based on selected export format
    document.getElementById('format').addEventListener('change', function() {
      var format = this.value;
      var button = document.getElementById('exportButton');

      if (format === 'pdf') {
        button.innerHTML = '<i class="fa fa-eye"></i> View, Print, Or Download Report';
      } else if (format === 'excel') {
        button.innerHTML = '<i class="fa fa-download"></i> Download Report';
      } else {
        // Reset to default if no format is selected
        button.innerHTML = '<i class="fa fa-eye"></i> View Report';
      }
    });

    // Trigger change events to set initial options and button text
    document.getElementById('table').dispatchEvent(new Event('change'));
    document.getElementById('format').dispatchEvent(new Event('change'));
  </script>
</body>
</html>
