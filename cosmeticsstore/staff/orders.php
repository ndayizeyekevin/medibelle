<?php
session_start();
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Orders Management</title>
  
  <!-- Sidebar -->
 <!-- Fav Icon -->
 <link rel="icon" href="../images/logo.jpg" type="image/x-icon">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="./css/style.css">
  <style>
    .side-bar1 {
        color: #00f;
    }
    .side-bar1 ul li{
        list-style: none;
        font-size: 20px;
        display: inline;
        padding: 5px;
        

    }
    
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <?php include './includes/header.php'; ?>

  <!-- Main Content Area with min-height 100vh -->
  <div class="content d-flex flex-column min-vh-100 mb-5">
    <!-- Header with dark background and 20vh height -->
    <div class="header bg-dark">
      <h1>Orders Management</h1>
    </div>

    <!-- Orders Menu -->
    <div class="menu side-bar1 text-center mt-3">
    <ul>
            <li><i class="bi bi-cart"></i> <a href="./orders.php">Prescription Orders</a></li>
            <li><i class="bi bi-cart"></i> <a href="./orders.php">Orders</a></li>
        </ul>
    </div>
    
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
        maintainAspectRatio: false
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
        maintainAspectRatio: false
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
        maintainAspectRatio: false
      }
    });

    // Sidebar dropdown functionality for Products menu
    document.getElementById('products-menu').addEventListener('click', function() {
      this.classList.toggle('show');
    });
  </script>

  
</body>
</html>
