<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../auth");
    exit();
}
// Title
$title = 'Products Management';
?>

  <!-- Sidebar -->
  <?php include '../includes/header.php'; ?>

  
    
    
    <div class="mx-3">
      <h2 class="text-center mb-4">Products Management</h2>
      
      <!-- Filter Dropdown -->
      <div class="mb-3 mx-3">
        <label for="categoryFilter" class="form-label">Filter by Category:</label>
        <select id="categoryFilter" class="form-select">
          <option value="">All Categories</option>
          <option value="medicines">Medicines</option>
          <option value="mom-baby">Mom & Baby</option>
          <option value="nutrition">Nutrition</option>
          <option value="skin-care">Skin Care</option>
          <option value="personal-care">Personal Care</option>
          <option value="health-care">Health Care</option>
          <option value="allergy-relief">Allergy Relief</option>
        </select>
      </div>
      
      <!-- Table Container -->
      <div class="table-responsive mx-3 text-justify">
        <table id="productsTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Category</th>
              <th>Description</th>
              <th>Price</th>
              <th>Image</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <!-- Table Footer for Column Searches -->
          <tfoot>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Description</th>
              <th>Price</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
          <tbody>
            <?php
              require '../../includes/conn.php';
              $stmt = $pdo->query("SELECT * FROM products");
              $count = 1;
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$count}</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td style='width:7%;'>
                        <button class='btn btn-sm btn-success' data-bs-toggle='modal' data-bs-target='#viewImageModal{$row['id']}'>
                          <i class='fas fa-eye'></i> View
                        </button>
                      </td>";
                echo "<td style='width:7%;'>
                      <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>
                        <i class='fas fa-edit'></i> Edit
                      </button>
                    </td>";              
                echo "<td style='width:7%;'>
                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['id']}'>
                          <i class='fas fa-trash'></i> Delete
                        </button>
                      </td>";
                echo "</tr>";
                
                // --- View Image Modal --- //
                echo "<div class='modal fade' id='viewImageModal{$row['id']}' tabindex='-1' aria-hidden='true'>
                        <div class='modal-dialog'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title'>Product Image</h5>
                              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body text-center'>
                              <img src='../../" . htmlspecialchars($row['image']) . "' class='img-fluid' alt='Product Image'>
                            </div>
                          </div>
                        </div>
                      </div>";
                
                // --- Edit Product Modal --- //
                echo "<div class='modal fade' id='editModal{$row['id']}' tabindex='-1' aria-hidden='true'>
                        <div class='modal-dialog'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title'>Edit Product</h5>
                              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form id='editForm{$row['id']}' action='./update-product.php' method='POST'>
                              <div class='modal-body'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <div class='mb-3'>
                                  <label class='form-label'>Name</label>
                                  <input type='text' class='form-control' name='name' value='" . htmlspecialchars($row['name']) . "' required>
                                </div>
                                <div class='mb-3'>
                                  <label class='form-label'>Category</label>
                                  <select class='form-select' name='category' required>
                                    <option value='' disabled>Select a category</option>
                                    <option value='medicines' " . ($row['category'] == 'medicines' ? 'selected' : '') . ">Medicines</option>
                                    <option value='mom-baby' " . ($row['category'] == 'mom-baby' ? 'selected' : '') . ">Mom & Baby</option>
                                    <option value='nutrition' " . ($row['category'] == 'nutrition' ? 'selected' : '') . ">Nutrition</option>
                                    <option value='skin-care' " . ($row['category'] == 'skin-care' ? 'selected' : '') . ">Skin Care</option>
                                    <option value='personal-care' " . ($row['category'] == 'personal-care' ? 'selected' : '') . ">Personal Care</option>
                                    <option value='health-care' " . ($row['category'] == 'health-care' ? 'selected' : '') . ">Health Care</option>
                                    <option value='allergy-relief' " . ($row['category'] == 'allergy-relief' ? 'selected' : '') . ">Allergy Relief</option>
                                  </select>
                                </div>
                                <div class='mb-3'>
                                  <label class='form-label'>Description</label>
                                  <textarea class='form-control' name='description' required>" . htmlspecialchars($row['description']) . "</textarea>
                                </div>
                                <div class='mb-3'>
                                  <label class='form-label'>Price</label>
                                  <input type='number' step='0.01' class='form-control' name='price' value='{$row['price']}' required>
                                </div>
                              </div>
                              <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                <button type='submit' class='btn btn-primary'>Save Changes</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>";
                
                // --- Delete Product Modal --- //
                echo "<div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1' aria-hidden='true'>
                        <div class='modal-dialog'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title'>Confirm Delete</h5>
                              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                              Are you sure you want to delete this product?
                            </div>
                            <div class='modal-footer'>
                              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                              <button type='button' class='btn btn-danger confirm-delete' data-id='{$row['id']}'>Delete</button>
                            </div>
                          </div>
                        </div>
                      </div>";
              // Increment Counter
              $count++;
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>
  
  
  <script>
    $(document).ready(function () {
      // Initialize DataTable with refined functionalities
      var table = $('#productsTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthMenu: [5, 10, 25, 50, 100],
        pageLength: 5,
        info: true,
        responsive: true,
        pagingType: "simple_numbers",
        initComplete: function () {
          // Enable per-column search for columns 0-4 (skip Image and Action columns)
          this.api().columns().every(function (index) {
            if (index < 5) {
              var column = this;
              $('<input type="text" placeholder="Search" class="form-control form-control-sm" />')
                .appendTo($(column.footer()).empty())
                .on('keyup change clear', function () {
                  if (column.search() !== this.value) {
                    column.search(this.value).draw();
                  }
                });
            }
          });
        }
      });
      
      // Custom filter: Filter by Category (Column index 2)
      $('#categoryFilter').on('change', function() {
        var selectedCategory = $(this).val();
        if (selectedCategory) {
          table.column(2).search('^' + selectedCategory + '$', true, false).draw();
        } else {
          table.column(2).search('').draw();
        }
      });
      
      // Handle delete confirmation with SweetAlert2
      $('.confirm-delete').click(function(){
        var id = $(this).data('id');
        Swal.fire({
          title: 'Confirm Delete',
          text: 'Are you sure you want to delete this product?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if(result.isConfirmed){
            window.location.href = 'delete-product.php?id=' + id;
          }
        });
      });
    });
  </script>
</body>
</html>
