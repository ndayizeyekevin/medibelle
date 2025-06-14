<?php
// profile.php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../auth");
    exit();
}

// Title
$title = "Dashboard";
require '../includes/conn.php'; // Include your database connection file

// Fetch user count
$stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
$user_count = $stmt->fetch()['user_count'];

// Fetch orders by status
$stmt = $pdo->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$order_labels = json_encode(array_column($orders, 'status'));
$order_data = json_encode(array_column($orders, 'count'));

// Fetch prescriptions by status
$stmt = $pdo->query("SELECT status, COUNT(*) as count FROM prescriptions GROUP BY status");
$prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$presc_labels = json_encode(array_column($prescriptions, 'status'));
$presc_data = json_encode(array_column($prescriptions, 'count'));
?>

  <!-- Sidebar Navigation -->
  <!--Include the sidebar  -->
  <?php include './includes/header.php'; ?>



 
    
    <!-- Charts Container -->
    <div class="charts-container container flex-grow-1 mt-4">
      <div class="row">
        <div class="col-md-4 mb-4">
          <canvas id="usersChart" class="chart-canvas"></canvas>
        </div>
        <div class="col-md-4 mb-4">
          <canvas id="ordersChart" class="chart-canvas"></canvas>
        </div>
        <div class="col-md-4 mb-4">
          <canvas id="prescChart" class="chart-canvas"></canvas>
        </div>
      </div>
    </div>
    
    
  </div>

  <!-- Footer at the bottom -->
  <?php
    include './includes/footer.php';
  ?>
 

  <!-- PHP to pass data to JavaScript -->
  <script>
    const userCount = <?php echo $user_count; ?>;
    const orderLabels = <?php echo $order_labels; ?>;
    const orderData = <?php echo $order_data; ?>;
    const prescLabels = <?php echo $presc_labels; ?>;
    const prescData = <?php echo $presc_data; ?>;
  </script>

  <!-- Chart.js Scripts -->
  <script>
    // Users Chart - Bar Chart
    new Chart(document.getElementById('usersChart'), {
      type: 'bar',
      data: {
        labels: ['Users'],
        datasets: [{
          label: 'Total Users',
          data: [userCount],
          backgroundColor: 'blue'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'System Users'
          }
        }
      }
    });

    // Orders Chart - Pie Chart
    new Chart(document.getElementById('ordersChart'), {
      type: 'pie',
      data: {
        labels: orderLabels,
        datasets: [{
          data: orderData,
          backgroundColor: ['red', 'green', 'yellow']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Regular Orders'
          }
        }
      }
    });

    // Prescriptions Chart - Doughnut Chart
    new Chart(document.getElementById('prescChart'), {
      type: 'doughnut',
      data: {
        labels: prescLabels,
        datasets: [{
          data: prescData,
          backgroundColor: ['purple', 'orange', 'cyan']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Prescription Orders'
          }
        }
      }
    });

    
  </script>

  
</body>
</html>
