<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link rel="icon" href="./images/logo.jpg" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="./css/style.css">
  <style>
    .loading-placeholder {
      font-size: 20px;
      color: #888;
      text-align: center;
      padding: 50px 0;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <?php include './includes/nav.php'; ?>

  <div class="container my-4">
      <h1 class="mb-4">Your Cart</h1>
      
      <div class="mb-3">
          <button class="btn btn-primary px-3 py-2" onclick="window.location.href='/cosmeticsstore/'">Continue Shopping</button>
      </div>
      
      <div class="row" id="cartItems">
          <!-- Cart items will be rendered here -->
          <div class="loading-placeholder">Loading products...</div>
      </div>
      
      <div class="mt-4 text-primary">
          <h4>Total Items: <span id="totalItems">0</span></h4>
          <h4>Total Price: <span id="totalPrice">0</span> RWF</h4>
      </div>

      <h3 class="mt-5">Customer Information</h3>
      <form action="./save-order.php" method="POST" id="checkoutForm">
          <div class="mb-3">
              <label for="full_name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Jane Doe" minlength="7" required>
          </div>
          <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="example@example.com" minlength="7" required>
          </div>
          <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="1" minlength="7" placeholder="Kigali" required></textarea>
          </div>
          <div class="mb-3">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="number" class="form-control" id="phone" name="phone" placeholder="Example: 0788123456" pattern="^\d{10}$" minlength="5" required>
          </div>
          <input type="hidden" id="cartData" name="cart">
          <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
      </form>
  </div>

  <?php include './includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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
                <img src="${item.image}" class="card-img-top" alt="${item.name}">
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
