
<!-- Include Header -->
<?php
    $title = "Terms of Service and Policy of Use";
    include '../includes/header.php';
?>
  <div class="container">
    <div class="container mb-4">
      <h1 class="mb-4">Terms of Service and Policy of Use</h1>
      <p><em>Last Updated: </em><strong>June 2<sup>nd</sup>, 2025</strong></p>
      
      <div class="section">
        <h2>1. Introduction</h2>
        <p>Welcome to <?= $config['companyName']; ?>. These Terms of Service ("Terms") govern your use of our website and services. By accessing or using our website, you agree to these Terms. Please read them carefully.</p>
      </div>

      <div class="section">
        <h2>2. Definitions</h2>
        <ul>
          <li><strong>"Client", "You", or "User":</strong> The individual or entity using our services.</li>
          <li><strong>"Product":</strong> Any item listed for sale on our website.</li>
          <li><strong>"Order":</strong> A purchase request made through our website.</li>
          <li><strong>"Price Adjustment Policy":</strong> Our policy regarding changes in product pricing before payment is completed.</li>
        </ul>
      </div>

      <div class="section">
        <h2>3. Acceptance of Terms</h2>
        <p>By using our services, you agree to be bound by these Terms, including our Refund and Price Adjustment Policy. If you do not agree, please refrain from using our website.</p>
      </div>

      <div class="section">
        <h2>4. Order Process</h2>
        <p>When you place an order, you agree to provide accurate and complete information. We reserve the right to cancel or refuse orders if the information provided is inaccurate or incomplete.</p>
      </div>

      <div class="section">
        <h2>5. Pricing and Payment</h2>
        <p>All product prices are displayed in <strong>Rwandan Francs (RWF)</strong>. Prices are subject to change without prior notice.
        </p>
        <p>
          Payments are made when our agent contacts you to confirm the order. The agent will also inform you of the available payment methods.
        </p>
      </div>

      <div class="section">
        <h2>6. Price Adjustment Policy</h2>
        <p>In the event that the price of a product changes after you place an order but before payment is completed:</p>
        <ul>
          <li>If the product price increases, you are required to pay the additional amount to complete the transaction.</li>
          <li>If the product price decreases, you will be refunded the difference.</li>
        </ul>
        <p>By confirming your order, you acknowledge and accept this price adjustment policy.</p>
      </div>

      <div class="section">
        <h2>7. Shipping and Delivery</h2>
        <p>We will ship products to the address provided during the order process. Delivery times may vary based on your location. Any shipping costs are your responsibility unless otherwise specified.</p>
      </div>

      <div class="section">
        <h2>8. Returns and Refunds</h2>
        <p>If you are not satisfied with your purchase, please refer to our Return Policy for instructions on how to request a return or refund. Refunds, if applicable, will be processed in accordance with our Refund Policy.</p>
      </div>

      <div class="section">
        <h2>9. Intellectual Property</h2>
        <p>All content on our website—including text, images, logos, and other media—is the property of <?= $config['companyName']; ?> or its licensors and is protected by applicable intellectual property laws. You may not reproduce, distribute, or create derivative works without our express permission.</p>
      </div>

      <div class="section">
        <h2>10. Limitation of Liability</h2>
        <p>To the maximum extent permitted by law, <?= $config['companyName']; ?> shall not be liable for any direct, indirect, incidental, or consequential damages arising from your use or inability to use our services. Our total liability shall not exceed the amount paid for the product.</p>
      </div>

      <div class="section">
        <h2>11. Indemnification</h2>
        <p>You agree to indemnify and hold harmless <?= $config['companyName']; ?> from any claims, damages, or expenses arising from your use of our services or violation of these Terms.</p>
      </div>

      <div class="section">
        <h2>12. Changes to Terms</h2>
        <p>We reserve the right to modify these Terms at any time. Any changes will be effective immediately upon posting on our website. Your continued use of our services constitutes your acceptance of the updated Terms.</p>
      </div>

      <div class="section">
        <h2>13. Contact Information</h2>
        <p>If you have any questions about these Terms or our policies, please contact us at:</p>
        <p>
        <h4 class="text-primary">
          <?= $config['companyName']; ?></strong>
        </h4>
        <h4 class="text-primary">
          <?= $config['location']; ?>
        </h4>
          <strong class="text-primary">Email:</strong> <?= $config['email']; ?>
        </p>
        <p>
          
        <strong class="text-primary">Phone:</strong> <?= $config['number']; ?>
        </p>
      </div>

      <div class="section">
        <h2>14. Conclusion</h2>
        <p>We appreciate your business and trust. Our goal is to provide you with quality products and a transparent, fair transaction process. Thank you for choosing <?= $config['companyName']; ?>.</p>
      </div>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>

  
</body>
</html>
