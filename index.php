<?php $title = 'Landing Page'; ?>
<?php include './includes/header.php'; ?>

<style>
  /* Preloader container */
  #loader {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: white;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 1;
    transition: opacity 0.6s ease;
  }

  /* Animations */
  @keyframes bounceIn {
    0% { transform: scale(0.5); opacity: 0; }
    60% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(1); }
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Marquee styles */
  .marquee {
    overflow: hidden;
    position: relative;
    width: 100%;
    background: #fff;
    padding: 1rem 0;
  }
  .marquee .scrolling-div {
    display: inline-flex;
    gap: 1.5rem;
    animation: scroll-left 12s linear infinite;
  }
  .marquee:hover .scrolling-div {
    animation-play-state: paused;
  }
  @keyframes scroll-left {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
  }
  .category-item {
    flex: 0 0 auto;
    text-align: center;
    color: inherit;
    text-decoration: none;
  }
  .category-item img {
    display: block;
    max-width: 60px;
    margin: 0 auto 0.5rem;
  }

  /* Search bar */
  .search-bar {
    display: flex;
    gap: 0.5rem;
  }
  .search-bar input[type="text"] {
    flex-grow: 1;
    padding: 0.5rem;
  }
  .search-bar button {
    padding: 0.5rem 1rem;
  }

  /* Card adjustments */
  .card-img-top {
    object-fit: cover;
  }

</style>

<!-- Preloader spinner (hidden by default) -->
<div id="loader" style="display:none;">
  <img src="/cosmeticsstore/images/icon.png" alt="Logo" width="100" style="animation: bounceIn 1s ease;">
  <i class="fa fa-spinner fa-spin fa-3x text-primary mt-4"></i>
  <p class="mt-3 text-primary fs-4" style="animation: fadeInUp 1.2s ease;">
    Loading, please wait...
  </p>
</div>


  

<div class="main-content container">
  <div class="mt-4 align-items-center">
            
            <div class="prescription">
                <div class="search-upload-section">
                    <h3 class="px-2">Have A prescrition?</h3>
                    <a href="prescription" class="upload-btn">
                        <i class="fa fa-paperclip upload-btn-icon"></i> <!-- Attachment Icon -->
                        Upload prescription now
                    </a>
                </div>
            </div>
            <div class="container mt-3">
                    <strong>What are you looking for?</strong>
            </div>
            <div class="search-upload-section">
                
                
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <button>Search</button>
                </div>
            </div>
        </div>

  <h2 class="mx-3 mt-5">Product Categories</h2>
  <div class="mt-4 mb-4 border-bottom border-3 border-primary">
    <div class="marquee" aria-label="Product categories scrolling marquee">
      <div class="scrolling-div">
        <a class="category-item" href="./search/?category=medicines">
          <img src="./images/medicines.webp" alt="Medicines">
          <span>Medicines</span>
        </a>
        <a class="category-item" href="./search/?category=mom-baby">
          <img src="./images/mom-baby1.svg" alt="Mom & Baby">
          <span>Mom & Baby</span>
        </a>
        <a class="category-item" href="./search/?category=nutrition">
          <img src="./images/nutrition.jpg" alt="Nutrition">
          <span>Nutrition</span>
        </a>
        <a class="category-item" href="./search/?category=skin-care">
          <img src="./images/skin-care.jpg" alt="Skin Care">
          <span>Skin Care</span>
        </a>
        <a class="category-item" href="./search/?category=personal-care">
          <img src="./images/personal-care.png" alt="Personal Care">
          <span>Personal Care</span>
        </a>
        <a class="category-item" href="./search/?category=health-care">
          <img src="./images/health-care.svg" alt="Health Care">
          <span>Health Care</span>
        </a>
        <a class="category-item" href="./search/?category=allergy-relief">
          <img src="./images/allergy-relief.png" alt="Allergy Relief">
          <span>Allergy Relief</span>
        </a>
      </div>
    </div>
  </div>

  <main class="row mt-5" aria-live="polite" aria-label="Product listing">
    <?php
    require './includes/conn.php';

    try {
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $pdo->query("SELECT * FROM products");

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagePath = htmlspecialchars($row['image'], ENT_QUOTES);
        $productName = htmlspecialchars($row['name'], ENT_QUOTES);
        $price = htmlspecialchars($row['price'], ENT_QUOTES);
        $category = htmlspecialchars($row['category'], ENT_QUOTES);
        $productId = (int)$row['id'];

        echo "<article class='col-md-3 mb-4'>";
        echo "<div class='card h-100'>";
        echo "<img src='{$imagePath}' class='card-img-top h-100' alt='Image of {$productName}' loading='lazy'>";
        echo "<div class='card-body bg-dark text-light'>";
        echo "<h5 class='card-title text-start'>Name: {$productName}</h5>";
        echo "<p class='card-text'>Price: {$price} RWF</p>";
        echo "<p class='card-text'>Category: {$category}</p>";
        echo "<button class='btn btn-primary mb-3' onclick='showProductInfo({$productId})'>";
        echo "<i class='fas fa-info-circle me-2'></i>More Info</button>";
        echo "<div class='d-flex align-items-center'>";
        echo "<input type='number' class='form-control w-25' id='quantity{$productId}' value='1' min='1' aria-label='Quantity for {$productName}'>";
        echo "<button class='btn btn-primary w-50 addToCart mx-2' data-id='{$productId}' data-name='{$productName}' data-price='{$price}' data-image='{$imagePath}'>";
        echo "<i class='fas fa-cart-plus'></i> Add to Cart</button>";
        echo "</div></div></div></article>";
      }
    } catch (PDOException $e) {
      echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
  </main>
</div>

<?php include './includes/footer.php'; ?>

<!-- Modal for Product Info -->
<div class="modal fade" id="productInfoModal" tabindex="-1" aria-labelledby="productInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productInfoModalLabel">Product Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="productDescription"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Show the preloader only once per browser session
  document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('loader');
    // const mainContent = document.getElementById('main-content');

    if (!localStorage.getItem('firstVisit')) {
      // Show loader for 2 seconds
      loader.style.display = 'flex';
      // mainContent.style.display = 'none';

      setTimeout(() => {
        loader.style.opacity = 0;
        setTimeout(() => {
          loader.style.display = 'none';
          // mainContent.style.display = 'block';
          localStorage.setItem('firstVisit', 'true');
        }, 600);
      }, 2000);
    } else {
      // Directly show content if already visited before
      loader.style.display = 'none';
      // mainContent.style.display = 'block';
    }
  });
  // Define categories as an array of objects
  

  // Dynamically create categories



  function showProductInfo(productId) {
    $.ajax({
        url: 'includes/fetch_product_info.php',
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
        

  // Add to Cart button event (example - expand as needed)
  document.querySelectorAll('.addToCart').forEach(btn => {
    btn.addEventListener('click', e => {
      const id = e.currentTarget.dataset.id;
      const name = e.currentTarget.dataset.name;
      const price = e.currentTarget.dataset.price;
      const quantity = document.getElementById('quantity' + id).value;
      // TODO: Add real cart logic here
      // alert(`Added ${quantity} of ${name} to cart at ${price} each.`);
    });
  });
</script>
