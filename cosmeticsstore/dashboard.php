<?php
session_start();
require './includes/conn.php'; // Include your database connection file

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
  <title>Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Sidebar styles */
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background-color: #34495e;
      color: #fff;
      overflow-y: auto;
      transition: all 0.3s;
    }
    .sidebar .profile {
      padding: 20px;
      display: flex;
      align-items: center;
      border-bottom: 1px solid #2c3e50;
    }
    .sidebar .profile img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 15px;
    }
    .sidebar .profile span {
      font-size: 1rem;
      font-weight: bold;
    }
    .sidebar .search {
      padding: 15px;
      border-bottom: 1px solid #2c3e50;
    }
    .sidebar .search input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
    }
    .sidebar .menu ul {
      list-style: none;
      padding: 0;
    }
    .sidebar .menu ul li {
      padding: 10px 20px;
      cursor: pointer;
    }
    .sidebar .menu ul li:hover {
      background-color: #2c3e50;
    }
    .sidebar .menu ul li a {
      text-decoration: none;
      color: inherit;
    }
    .sidebar .menu ul li .submenu {
      list-style: none;
      padding-left: 15px;
      display: none;
    }
    .sidebar .menu ul li.show .submenu {
      display: block;
    }
    /* Main content styles */
    .content {
      margin-left: 250px;
    }
    .header {
      height: 20vh;
      background-color: #343a40;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .charts-container {
      padding: 20px;
    }
    /* Ensure all chart canvases have the same fixed height */
    .chart-canvas {
      height: 300px !important;
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <nav class="sidebar">
    <div class="profile">
      <img src="https://via.placeholder.com/50" alt="Profile Picture">
      <span>Admin Name</span>
    </div>
    <div class="search">
      <input type="text" placeholder="Search...">
    </div>
    <div class="menu">
      <ul>
        <li><i class="bi bi-house"></i> <a href="/">Dashboard</a></li>
        <li id="products-menu">
          <i class="bi bi-box"></i> Products <i class="bi bi-caret-down-fill float-end"></i>
          <ul class="submenu">
            <li><a href="/products">View Products</a></li>
            <li><a href="/products/add">Add Product</a></li>
          </ul>
        </li>
        <li><i class="bi bi-bar-chart"></i> <a href="/reports">Reports</a></li>
        <li><i class="bi bi-person"></i> <a href="/profile">Profile</a></li>
        <li><i class="bi bi-box-arrow-right"></i> <a href="/logout">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="content">
    <!-- Dark header with 20vh height -->
    <div class="header">
      <h1>Dashboard</h1>
    </div>
    <div class="charts-container container mt-4">
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
