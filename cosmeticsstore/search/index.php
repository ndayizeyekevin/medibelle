    <?php $title = 'Home'; ?>
     <!-- Include header -->
     <?php include '../includes/header.php'; ?>

    <div class="container my-4 container-fluid">
<?php
require '../includes/connection.php'; // Include database connection

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $_GET['category'];
    
    // Fetch products based on category
    $stmt = $dsn->prepare("SELECT * FROM products WHERE category = ? ORDER BY name ASC");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='row'>";
        while ($row = $result->fetch_assoc()) {
            // Image path
            $imagePath = "../" . $row['image'];
                        
            echo "<div class='col-md-3 mb-4'>";
                        echo "<div class='card h-100 hover:scale-105'>";
                        echo "<img src='{$imagePath}' class='card-img-top' alt='product image not found'>";
                        echo "<div class='card-body bg-dark text-light'>";
                        echo "<h5 class='card-title'>Name: {$row['name']}</h5>";
                        echo "<p class='card-text'>Price: {$row['price']} RWF</p>";
                        echo "<p class='card-text'>Category: {$row['category']}</p>";
                        echo '<button class="btn btn-primary mb-3" onclick="showProductInfo(' . $row['id'] . ')"><i class="fas fa-info-circle me-2"></i>More Info</button>';
                        
                        echo "<div class='d-flex align-items-center'>";
                        echo "<input type='number' class='form-control w-25' id='quantity{$row['id']}' value='1' min='1'>";
                        
                        echo "<button class='btn btn-primary w-50 addToCart mx-2' data-id='{$row['id']}' data-name='{$row['name']}' data-price='{$row['price']}' data-image='{$row['image']}'><i class='fas fa-cart-plus me-2'></i>Add to Cart</button>";
                        echo "</div></div></div></div>";
        }
        echo "</div>";
    } else {
        echo "<h2 class='text-muted mb-5'>No products found in this category.</h2>";
    }
    
    $stmt->close();
} else {
    echo "<h2 class='text-danger'>Invalid category selection.</h2>";
}
?>
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

        <!-- Footer -->

        <?php include '../includes/footer.php';?>

    




        <script>
            // Define categories as an array of objects
            

           // Dynamically create categories



            function showProductInfo(productId) {
                $.ajax({
                    url: '../fetch_product_info.php',
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