<?php
// orders.php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../auth");
    exit();
}
// Title
$title = 'Client Regular orders';
?>

  <!-- Header -->
  <?php include '../includes/header.php'; // your site header ?>
  <style>
    
    .table-hover tbody tr:hover td {
      background-color:rgb(147, 195, 244) !important; /* Light blue */
      color: #fff;
    }
    table {
      /* border-collapse: collapse; */
      width: 100%;
      margin-top: 10px;
    }
    th{
      border: 1px solid #1931ff !important;
      /* padding: 12px 3px !important; */
      text-align: left;
    }
    td{
      border: 1px solid !important;
    }
    th {
      background-color: #1031ff !important;
      color:rgb(255, 255, 255) !important;
    }
    
    table tr {
      page-break-inside: avoid !important;
    }
  </style>


    <!-- Main Content -->
    <div class="mx-4 my-4 flex-fill">
      <div class="mx-2">
      <h1 class="mb-4">Client Orders</h1>
        <div class="table-responsive">
          <table id="ordersTable" class="table table-bordered table-hover mt-4 mb-5">
            <thead>
              <tr>
                <th>#</th>
                <th>Client Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Edit Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Include the database connection
              include '../../includes/connection.php';
              $conn = $dsn;
              // Adjust the query to also fetch the order_status field
              $query = "SELECT DISTINCT o_id, full_name, email, phone, total_price, order_date, address, status 
                        FROM orders, customers 
                        WHERE customers.id = orders.customer_id AND status = 'pending' 
                        ORDER BY o_id";
              $result = $conn->query($query);

              $counter = 1;
              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($counter) . "</td>";
                      echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['total_price']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                      echo "<td>" . htmlspecialchars(ucwords($row['status'])) . "</td>";
                      // Actions column: view order items button
                      echo "<td>
                              <button class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#orderItemsModal'
                                onclick='fetchOrderItems(" . $row['o_id'] . ")'><i class='fas fa-eye'></i> View Items</button>
                            </td>";
                            
                      // Edit Status button (passes order id and current status)
                      echo "<td>
                              <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#orderStatusModal'
                                onclick='openOrderStatusModal(" . $row['o_id'] . ", " . json_encode($row['status']) . ")'>
                                <i class='fas fa-edit'></i> Edit Status
                              </button>
                            </td>";
                      
                      echo "</tr>";
                  $counter++;
                  }
              } else {
                  echo "<tr><td colspan='10' class='text-center'>No orders found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Order Items Modal -->
    <div class="modal fade" id="orderItemsModal" tabindex="-1" aria-labelledby="orderItemsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderItemsModalLabel">Order Items</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Item Name</th>
                  <th>Quantity</th>
                  <th>Order Price</th>
                  <th>New Price</th>
                  <th>Total</th>
                  <th>Total On New Price</th>
                </tr>
              </thead>
              <tbody id="orderItemsTableBody">
                <!-- Order items will be dynamically loaded here -->
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger px-3" data-bs-dismiss="modal"> Close X</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Status Edit Modal -->
    <div class="modal fade" id="orderStatusModal" tabindex="-1" aria-labelledby="orderStatusModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="../../includes/update-orders.php" method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="orderStatusModalLabel">Edit Order Status</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="order_id" name="order_id">
              <div class="mb-3">
                <label for="order_status" class="form-label">Order Status</label>
                <select class="form-select" id="order_status" name="order_status" required>
                  <option value="pending">Pending</option>
                  <option value="verified">Verify</option>
                  <option value="canceled">Cancel</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>


  <script>
    $(document).ready(function () {
      $('#ordersTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthMenu: [5, 10, 25, 50, 100],
        pageLength: 5
      });
    });

    function fetchOrderItems(orderId) {
      fetch(`../../includes/fetch_order_items.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(data => {
          const orderItemsTableBody = document.getElementById('orderItemsTableBody');
          orderItemsTableBody.innerHTML = '';
          // Calculating sum on order product price
          let sum = 0;
          // Calculating sum on product new price
          let totalCurrentPrice = 0;
          if (data.length > 0) {
            data.forEach(item => {
              sum += parseInt(item.total);
              // Calculating sum on product new price
              totalCurrentPrice += parseInt(item.quantity * item.price); 
              orderItemsTableBody.innerHTML += `
                <tr>
                  <td>${item.name}</td>
                  <td>${item.quantity}</td>
                  <td>${item.pricee} RWF</td>
                  <td>${item.price} RWF</td>
                  <td>${item.total} RWF</td>
                  <td class='text-primary'>${item.quantity * item.price} RWF</td>
                </tr>
              `;
            });
            orderItemsTableBody.innerHTML += `
              <tfoot>
                <tr>
                  <th colspan="4" class='bg-success text-light'><strong>Grand Total:</strong></th>
                  <th colspan="2" class='bg-success text-light'><strong>${sum} RWF</strong></th>
                </tr>
                <tr>
                  <th colspan="5" class='bg-dark text-light'><strong>Grand Total On New Price:</strong></th>
                  <th class='bg-dark text-light'><strong>${totalCurrentPrice} RWF</strong></th>
                </tr>
              </tfoot>
            `;
          } else {
            orderItemsTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No items found for this order</td></tr>';
          }
        })
        .catch(error => console.error('Error fetching order items:', error));
    }

    // Function to open the Order Status Modal and pre-fill with current status
    function openOrderStatusModal(orderId, currentStatus) {
      document.getElementById('order_id').value = orderId;
      document.getElementById('order_status').value = currentStatus;
    }
  </script>
</body>
</html>
