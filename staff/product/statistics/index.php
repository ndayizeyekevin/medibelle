<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../../logout.php");
    exit();
}

require '../../../includes/conn.php';

// Title
$title = "Products Statistics";

// If this request is for statistics data, process and return JSON.
if (isset($_GET['action']) && $_GET['action'] === 'load_statistics') {
    // Retrieve all products from the database
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize statistics arrays
    $categoryCounts = [];
    $categoryPriceSum = [];
    $categoryPriceCount = [];
    $priceRanges = [
        'Under 5000 RWF' => 0,
        '5000 RWF - 10000 RWF' => 0,
        '10000 RWF - 20000 RWF' => 0,
        'Over 20000 RWF' => 0
    ];

    // Loop through products to compute statistics
    foreach ($products as $product) {
        $category = $product['category'];
        $price = floatval($product['price']);
        
        if (!isset($categoryCounts[$category])) {
            $categoryCounts[$category] = 0;
            $categoryPriceSum[$category] = 0;
            $categoryPriceCount[$category] = 0;
        }
        $categoryCounts[$category]++;
        $categoryPriceSum[$category] += $price;
        $categoryPriceCount[$category]++;
        
        // Determine price range bucket
        if ($price < 5000) {
            $priceRanges['Under 5000 RWF']++;
        } elseif ($price < 10000) {
            $priceRanges['5000 RWF - 10000 RWF']++;
        } elseif ($price < 20000) {
            $priceRanges['10000 RWF - 20000 RWF']++;
        } else {
            $priceRanges['Over 20000 RWF']++;
        }
    }
    
    // Calculate average price per category
    $avgPriceByCategory = [];
    foreach ($categoryPriceSum as $cat => $sum) {
        $avgPriceByCategory[$cat] = $categoryPriceCount[$cat] > 0 ? round($sum / $categoryPriceCount[$cat], 2) : 0;
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'categoryCounts'   => $categoryCounts,
        'avgPriceByCategory' => $avgPriceByCategory,
        'priceRanges'      => $priceRanges
    ]);
    exit();
}
?>  

  <!-- Include external header (assumed lightweight) -->
  <?php include '../../includes/header.php'; ?>

  
    <div class="container mt-5">
      <h1 class="text-center mb-4">Products Statistics</h1>
      
      <!-- Chart 1: Products by Category -->
      <div class="chart-container" id="chart1-container">
        <div class="loading-placeholder">Loading data...</div>
        <canvas id="productsByCategoryChart" style="display: none;"></canvas>
      </div>
      
      <!-- Chart 2: Average Price by Category -->
      <div class="chart-container" id="chart2-container">
        <div class="loading-placeholder">Loading data...</div>
        <canvas id="avgPriceByCategoryChart" style="display: none;"></canvas>
      </div>
      
      <!-- Chart 3: Products by Price Range -->
      <div class="chart-container" id="chart3-container">
        <div class="loading-placeholder">Loading data...</div>
        <canvas id="priceRangeChart" style="display: none;"></canvas>
      </div>
    </div>

  <!-- Include external footer -->
  <?php include '../../includes/footer.php'; ?>

  <script>
    // Load statistics data asynchronously and render charts
    function loadStatistics() {
      fetch('index.php?action=load_statistics')
        .then(response => response.json())
        .then(data => {
          // Remove "Loading data..." placeholders and show canvases
          document.querySelectorAll('.loading-placeholder').forEach(el => el.style.display = 'none');
          document.getElementById('productsByCategoryChart').style.display = 'block';
          document.getElementById('avgPriceByCategoryChart').style.display = 'block';
          document.getElementById('priceRangeChart').style.display = 'block';

          // Chart 1: Products by Category (Doughnut Chart)
          const ctxCategory = document.getElementById('productsByCategoryChart').getContext('2d');
          new Chart(ctxCategory, {
            type: 'pie',
            data: {
              labels: Object.keys(data.categoryCounts),
              datasets: [{
                label: 'Products Count',
                data: Object.values(data.categoryCounts),
                backgroundColor: [
                  'rgba(255, 99, 132, 0.6)',
                  'rgba(54, 162, 235, 0.6)',
                  'rgba(255, 206, 86, 0.6)',
                  'rgba(75, 192, 192, 0.6)',
                  'rgba(153, 102, 255, 0.6)',
                  'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: 'Products by Category'
                }
              }
            }
          });

          // Chart 2: Average Price by Category (Bar Chart)
          const ctxAvgPrice = document.getElementById('avgPriceByCategoryChart').getContext('2d');
          new Chart(ctxAvgPrice, {
            type: 'bar',
            data: {
              labels: Object.keys(data.avgPriceByCategory),
              datasets: [{
                label: 'Average Price (RWF)',
                data: Object.values(data.avgPriceByCategory),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
              }]
            },
            options: {
              scales: { y: { beginAtZero: true } },
              plugins: {
                title: {
                  display: true,
                  text: 'Average Price by Category'
                }
              }
            }
          });

          // Chart 3: Products by Price Range (Bar Chart)
          const ctxPriceRange = document.getElementById('priceRangeChart').getContext('2d');
          new Chart(ctxPriceRange, {
            type: 'bar',
            data: {
              labels: Object.keys(data.priceRanges),
              datasets: [{
                label: 'Number of Products',
                data: Object.values(data.priceRanges),
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
              }]
            },
            options: {
              scales: { y: { beginAtZero: true } },
              plugins: {
                title: {
                  display: true,
                  text: 'Products by Price Range'
                }
              }
            }
          });
        })
        .catch(error => {
          console.error("Error loading statistics:", error);
          document.querySelectorAll('.loading-placeholder').forEach(el => el.textContent = 'Error loading data');
        });
    }
    
    // When the DOM content is loaded, call the function
    window.addEventListener('DOMContentLoaded', loadStatistics);
  </script>
</body>
</html>
