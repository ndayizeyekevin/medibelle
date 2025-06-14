<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../../auth");
    exit();
}
// Title
$title = "Add New Product";

?>


  <!--Includic the sidebar  -->
<?php include '../../includes/header.php'; ?>

  
    
    <div class="container">
      <h2 class="text-center mb-4">Add New Product</h2>
      <div class="form-container border p-4 shadow-sm">
        <form action="./insert-product.php" method="POST" enctype="multipart/form-data" class="staff-form">
          <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input 
              type="text" 
              class="form-control" 
              id="productName" 
              name="name" 
              placeholder="Enter product name" 
              required>
          </div>
          <div class="mb-3">
            <label for="productCategory" class="form-label">Category</label>
            <select 
              class="form-select" 
              id="productCategory" 
              name="category" 
              required>
              <option value="" disabled selected>Select a category</option>
              <option value="medicines">Medicines</option>
              <option value="mom-baby">Mom &amp; Baby</option>
              <option value="nutrition">Nutrition</option>
              <option value="skin-care">Skin Care</option>
              <option value="personal-care">Personal Care</option>
              <option value="health-care">Health Care</option>
              <option value="allergy-relief">Allergy Relief</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="productDescription" class="form-label">Description</label>
            <textarea 
              class="form-control" 
              id="productDescription" 
              name="description" 
              rows="4" 
              placeholder="Enter product description" 
              required></textarea>
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">Price</label>
            <input 
              type="number" 
              step="0.01" 
              class="form-control" 
              id="productPrice" 
              name="price" 
              placeholder="0.00" 
              required>
          </div>
          <div class="mb-3">
            <label for="productImage" class="form-label">Product Image</label>
            <input 
              type="file" 
              class="form-control" 
              id="productImage" 
              name="image">
          </div>
          <div class="d-flex justify-content-between">
            <button type="reset" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Clear Form Data
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-plus"></i> Add Product
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <?php include '../../includes/footer.php'; ?>
  
</body>
</html>
