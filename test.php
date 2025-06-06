<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Listing</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <div class="container my-4">
    <h1 class="mb-4">Products</h1>
    <div class="row">
      <?php
      // Include the database connection using PDO
      require './includes/conn.php';
      
      
      // Fetch basic product info from the products table
      $stmt = $pdo->query("SELECT id, name, price FROM products");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<div class="col-md-4 mb-3">';
          echo '  <div class="card">';
          echo '    <div class="card-body">';
          echo '      <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
          echo '      <p class="card-text">Price: ' . htmlspecialchars($row['price']) . ' RWF</p>';
          echo '      <button class="btn btn-primary" onclick="showProductInfo(' . $row['id'] . ')">More Info</button>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
      }
      ?>
    </div>
  </div>

  <!-- Modal for Product Info -->
  <div class="modal fade" id="productInfoModal" tabindex="-1" aria-labelledby="productInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="productInfoModalLabel">Product Details</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
           <!-- Product details will be loaded here -->
           <p id="productDescription"></p>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </div>
      </div>
    </div>
  </div>

  <script>
    function showProductInfo(productId) {
      $.ajax({
        url: 'fetch_product_info.php',
        type: 'GET',
        data: { product_id: productId },
        dataType: 'json',
        success: function(data) {
          if(data.error) {
            alert(data.error);
          } else {
            // Set the modal title and description using the fetched data
            $('#productInfoModalLabel').text(data.name);
            $('#productDescription').html(
              '<strong>Price:</strong> ' + data.price + ' RWF<br><br>' +
              '<strong>Description:</strong><br>' + data.description
            );
            // Show the modal
            $('#productInfoModal').modal('show');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error fetching product info:', error);
        }
      });
    }
  </script>
</body>
</html>
