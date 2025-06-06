  <?php $title = 'Shopping Cart'; ?>
  <!-- Include header -->
  <?php include '../includes/header.php'; ?>

  <div class="container main-content">
      <h1 class="mb-4">Your Cart</h1>
      
      <div class="mb-3">
          <button class="btn btn-primary px-3 py-2" onclick="window.location.href='../'"><i class='fas fa-cart-plus me-2'></i>Continue Shopping</button>
      </div>
      
      <div class="row" id="cartItems">
          <!-- Cart items will be rendered here -->
          <div class="loading-placeholder">Loading products...</div>
      </div>
      
      <div class="mt-4 text-primary">
          <h4>Total Items: <span id="totalItems">0</span></h4>
          <h4>Total Price: <span id="totalPrice">0</span> RWF</h4>
      </div>

      <div class="col-md-8">
        <h3 class="mt-5">Customer Information</h3>
        <form action="../save-order.php" method="POST" id="checkoutForm">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Jane Doe" minlength="7" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@example.com" minlength="4" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="1" minlength="3" placeholder="Kigali" required></textarea>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Example: 0788123456" minlength="10" maxlength="10"
                        pattern="^(078|079|072|073)\d{7}$"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        maxlength="10"
                        title="Enter a 10-digit number starting with 078, 079, 072, or 073" required>
            </div>
            <input type="hidden" id="cartData" name="cart">
            <button type="submit" class="btn btn-primary fs-5"><i class="fas fa-cart-arrow-down fa-lg me-2"></i> Proceed to Checkout</button>
        </form>
      </div>
  </div>

  <?php include '../includes/footer.php'; ?>
  <script>
  
    // Function to render cart items from localStorage
    function renderCart() {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      let totalItems = 0;
      let totalPrice = 0;
      let html = '';

      if (cart.length === 0) {
        html = '<div class="loading-placeholder">Your cart is empty.</div>';
        $('button[type="submit"]').prop('disabled', true);
      } else {
        $('button[type="submit"]').prop('disabled', false);
        cart.forEach((item, index) => {
          const itemTotal = item.price * item.quantity;
          totalItems += item.quantity;
          totalPrice += itemTotal;
          html += `
            <div class="col-md-3 mb-4 cart-item">
              <div class="card">
                <img src="../${item.image}" class="card-img-top" alt="${item.name}">
                <div class="card-body bg-dark text-light position-relative">
                  <h5 class="card-title">${item.name}</h5>
                  <p class="card-text">Price: ${item.price} RWF</p>
                  <p class="card-text">Quantity: ${item.quantity}</p>
                  <p class="card-text">Total: ${itemTotal} RWF</p>
                  <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="removeFromCart(${index})">
                    Remove <i class="bi bi-x-circle"></i>
                  </button>
                </div>
              </div>
            </div>
          `;
        });
      }
      $('#cartItems').html(html);
      $('#totalItems').text(totalItems);
      $('#totalPrice').text(totalPrice);
      $('#cartData').val(JSON.stringify(cart));
    }

    // Initial render of cart on page load
    renderCart();

    // Prevent checkout if cart is empty
    $('#checkoutForm').on('submit', function(e) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      if (cart.length === 0) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Your Cart is Empty',
          text: 'Please add items to your cart before proceeding to checkout.',
          confirmButtonText: 'OK'
        });
      }
    });

    // Function to remove an item from the cart and re-render
    function removeFromCart(index) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
      // Update the cart counters in the navbar if the function exists
      if (typeof updateCartCounter === 'function') {
        updateCartCounter();
      }
    }
  </script>
</body>
</html>
