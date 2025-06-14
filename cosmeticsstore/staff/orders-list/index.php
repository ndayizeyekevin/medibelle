<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../login.php");
    exit();
}
// Title
$title = "Orders List";

require '../../includes/conn.php'; // Adjust the path to your connection file

// Get status filter from GET, default to "pending"
$status = isset($_GET['status']) ? $_GET['status'] : 'pending';
$allowedStatuses = ['pending', 'verified', 'canceled'];
if (!in_array($status, $allowedStatuses)) {
    $status = 'pending';
}


?>

  <!-- Sidebar Navigation (if any) -->
  <?php include '../includes/header.php'; ?>
  


  <div class="mx-5 my-4">
    <!-- Status Navigation -->
    <nav class="nav nav-pills nav-status mb-4">
      <a class="nav-link text <?php echo ($status === 'pending') ? 'active' : ''; ?>" href="./">Pending Orders</a>
      <a class="nav-link text <?php echo ($status === 'verified') ? 'active' : ''; ?>" href="?status=verified">Verified Orders</a>
      <a class="nav-link text <?php echo ($status === 'canceled') ? 'active' : ''; ?>" href="?status=canceled">Canceled Orders</a>
    </nav>

    <h1 class="mb-4"><?php echo ucfirst($status); ?> Orders</h1>
    
    <div class="">
        <div class="table-responsive">
            <table id="ordersTable" class="table table-bordered table-striped">
                <thead class="bg-dark text-light">
                  <tr>
                      <th>#</th>
                      <th>Client Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Address</th>
                      <th>Total Amount</th>
                      <th>Date</th>
                      <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                // Prepare the query to fetch orders with the selected status.
                // Adjust the query as needed to match your database structure.
                $stmt = $pdo->prepare("
                    SELECT DISTINCT o_id, full_name, email, phone, address, total_price, order_date, status 
                    FROM orders 
                    INNER JOIN customers ON customers.id = orders.customer_id 
                    WHERE status = ? 
                    ORDER BY o_id DESC
                ");
                $stmt->execute([$status]);
                $count = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($count) . "</td>";
                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['total_price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                    switch($status){
                      case 'pending':
                        echo "<td class='text-warning bg-dark'>" . htmlspecialchars(ucwords($row['status'])) . "</td>";
                        echo "</tr>";
                        break;
                      case 'verified':
                        echo "<td class='text-primary'>" . htmlspecialchars(ucwords($row['status'])) . "</td>";
                        echo "</tr>";
                        break;
                      case 'canceled':
                          echo "<td class='text-danger bg-dark'>" . htmlspecialchars(ucwords($row['status'])) . "</td>";
                          echo "</tr>";
                          break;
                      
                      
                    }
                    $count++;
                    
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
  </div>

  <!-- Footer (if any) -->
  <?php include '../../includes/footer.php'; ?>

  <script>
    $(document).ready(function () {
      $('#ordersTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        responsive: true,
        language: {
          search: "Search Orders:"
        }
      });
    });
  </script>
</body>
</html>
