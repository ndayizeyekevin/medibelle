

  
  <!-- Footer Section -->
  <footer class="bg-dark text-light py-4 mt-auto shadow-lg">
    <div class="container mt-3">
      <!-- Developer Info (if any) can be added here -->
  
      <div class="row g-4 quick-links">
        <!-- Quick Links -->
        <div class="col-md-6 col-lg-4">
          <h2 class="fs-5 mb-3 text-primary">Quick Links</h2>
          <ul class="list-unstyled d-flex flex-column gap-2" id="menu">
            <li><a href="/cosmeticsstore" class="link-light link-offset-2 text-decoration-underline">Shop</a></li>
            <li><a href="./#mission" class="link-light link-offset-2 text-decoration-underline">Mission</a></li>
            <li><a href="/cosmeticsstore/about-us" class="link-light link-offset-2 text-decoration-underline">About Us</a></li>
            <li><a href="/cosmeticsstore/contact" class="link-light link-offset-2 text-decoration-underline">Contact Us</a></li>
            <li><a href="/cosmeticsstore/login.php" class="link-light link-offset-2 text-decoration-underline">Login</a></li>
          </ul>
        </div>
  
        <!-- Contact Info -->
        <div class="col-md-6 col-lg-4 contacts">
          <div class='border-bottom mb-5 justify-content-start'>
            <h4>Need Help</h4>
            <p class="my-3">
              <a href="tel:<?= $config['phone']; ?>" class="btn btn-outline-light">
                <i class="fas fa-phone-volume fa-lg me-2" aria-hidden="true"></i> <?= $config['number']; ?>
              </a>
            </p>
            <p>
              <a href="mailto:<?= $config['email']; ?>" class="btn btn-outline-light" target="_blank">
                <i class="fas fa-envelope fa-lg me-2" aria-hidden="true"></i> <?= $config['email']; ?>
              </a>
            </p>
            <p>
              <a href="https://wa.me/<?= $config['phone']; ?>" class="btn btn-outline-light" style="text-decoration: none;" target="_blank">
                <i class="fab fa-whatsapp fa-lg me-2" aria-hidden="true"></i> <?= $config['number']; ?>
              </a>
            </p>

          </div>
  
          <div class="mt-0 socials">
            <!-- Social Media Links (Font Awesome) -->
            <a href="https://www.facebook.com" class="text-white me-3" style="text-decoration: none;" target="_blank">
              <i class="fab fa-facebook-f" style="font-size: 24px;" aria-hidden="true"></i>
            </a>
            <a href="https://www.twitter.com" class="text-white me-3" style="text-decoration: none;" target="_blank">
              <i class="fab fa-twitter" style="font-size: 24px;" aria-hidden="true"></i>
            </a>
            <a href="https://www.linkedin.com" class="text-white me-3" style="text-decoration: none;" target="_blank">
              <i class="fab fa-linkedin-in" style="font-size: 24px;" aria-hidden="true"></i>
            </a>
            <a href="https://www.instagram.com" class="text-white" style="text-decoration: none;" target="_blank">
              <i class="fab fa-instagram" style="font-size: 24px;" aria-hidden="true"></i>
            </a>

          </div>
        </div>
  
        <!-- Google Map -->
        <div class="col-12 col-lg-4">
          <div class="ratio ratio-16x9">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10245.532441156592!2d30.061623!3d-1.946876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19ddf899c2d7a27f%3A0x77a4b7302b4e8eb9!2sKK%2017%20Ave%2C%20Kigali!5e0!3m2!1sen!2srw!4v1692802445412!5m2!1sen!2srw"
              allowfullscreen
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>
  
      <!-- Copyright Section -->
      <div class="text-center mt-5 pt-3 border-top border-secondary">
        <p class="mb-2 text-center">&copy; 
          <?php 
            $date = date('Y');
            if($date > 2025){
              echo "2025 - ". $date;
            } else{
              echo date('Y');
            }
          ?> <?= $config['companyName']; ?>. All rights reserved.
        </p>
        <div class="d-flex justify-content-center gap-3" id="menu">
          <a href="#" class="link-light link-offset-2 text-decoration-underline">Privacy Policy</a>
          <a href="/cosmeticsstore/termsandpolicy" target="_blank" class="link-light link-offset-2 text-decoration-underline">Terms of Use</a>
          <a href="#" class="link-light link-offset-2 text-decoration-underline">Licences</a>
        </div>
      </div>
    </div>
  </footer>
  
  <!-- Scroll to Top Button -->
  <div id="scrollToTopBtn" title="Go to top"><i class="fas fa-chevron-up"></i></div>
 
  <!-- Place scripts at the bottom for faster loading -->
  <script src="/cosmeticsstore/assets/js/jquery.min.js"></script>
  <!-- Bootstrap5 Js -->
  <script src="/cosmeticsstore/assets/js/bootstrap.bundle.min.js"></script>
  <!-- Popper JS -->
  <!-- <script src="/dams/assetss/js/popper.min.js"></script> -->

  <!-- DataTables JS -->
  <script src="/cosmeticsstore/assets/js/jquery.dataTables.min.js"></script>
  <script src="/cosmeticsstore/assets/js/dataTables.bootstrap5.min.js"></script>
  <!-- Chart.js -->
  <script src="/cosmeticsstore/assets/js/chart.umd.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
  <script src="/cosmeticsstore/js/script.js"></script>
  <script>
    // Hover Effects on Quick Links
    const menuLinks = document.querySelectorAll('#menu a');
    menuLinks.forEach(link => {
      link.addEventListener('mouseover', () => {
        link.style.color = '#0000ff'; // Change color on hover
      });
      link.addEventListener('mouseout', () => {
        link.style.color = 'white'; // Revert color when not hovering
      });
    });
    
    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', () => {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const cartCountElement = document.getElementById('cartCount');
      if(cartCountElement) {
        cartCountElement.textContent = cart.length;
      }
    });
    
    // Add product to cart functionality
    const addToCartButtons = document.querySelectorAll('.addToCart');
    addToCartButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        const productId = event.target.dataset.id;
        const productName = event.target.dataset.name;
        const productPrice = parseFloat(event.target.dataset.price);
        const productImage = event.target.dataset.image;
        const quantityInput = document.getElementById('quantity' + productId);
        const quantity = parseInt(quantityInput.value);
  
        const product = {
          id: productId,
          name: productName,
          price: productPrice,
          image: productImage,
          quantity: quantity
        };
  
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
  
        // Check if the product is already in the cart
        const productIndex = cart.findIndex(item => item.id === product.id);
  
        if (productIndex === -1) {
          cart.push(product);
        } else {
          cart[productIndex].quantity += product.quantity;
        }
  
        localStorage.setItem('cart', JSON.stringify(cart));
  
        // Update cart count if element exists
        const cartCountElement = document.getElementById('cartCount');
        if(cartCountElement) {
          cartCountElement.textContent = cart.length;
        }
      });
    });
    
    
  </script>
