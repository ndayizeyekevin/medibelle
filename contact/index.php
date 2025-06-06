<?php
session_start();
  $title = 'ContactUs';
  include '../includes/header.php';
  ?>
  <!-- Loader Overlay -->
  <div id="loaderOverlay">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Sending...</span>
    </div>
    <p>Sending your message...</p>
  </div>

  <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    $currentURI = $_SERVER['REQUEST_URI'];
  ?>


  <!-- Contact Us Section -->
  <section class="main-content container mb-5">
    <div class="contact-header">
      <h1>Contact Us</h1>
      <p>Have questions or feedback? We're here to help! Reach out to us using the form below, and we'll get back to you as soon as possible.</p>
    </div>
    
    <!-- Displaying the response from the server -->
   <?php 
    if (isset($_SESSION['contact_message'])) {

      echo $_SESSION['contact_message'] ;
      unset($_SESSION['contact_message']);
    }
   ?>
    <div class="row">
      <!-- Contact Form -->
      <div class="col-md-8">
        <form id="contactForm" action="contact_submit.php" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
          </div>
          <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="6" placeholder="Your message here..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary p-3"><i class="fas fa-paper-plane fa-lg me-2"></i> Send Message</button>
        </form>
      </div>
      <!-- Contact Information -->
      <div class="col-md-4 text-primary">
        <div class="contact-info">
          <h3 class=" text-dark">Get in Touch</h3>
          <p><i class="fas fa-map-marker-alt"></i> <?= $config['location']; ?></p>
          <p><i class="fas fa-phone"></i> <?= $config['number']; ?></p>
          <p><i class="fas fa-envelope"></i> <?= $config['email']; ?></p>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>
 
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const contactForm = document.getElementById('contactForm');
      const loaderOverlay = document.getElementById('loaderOverlay');
      
      contactForm.addEventListener('submit', function(e) {
        // Show the loader overlay immediately upon form submission
        loaderOverlay.style.display = 'flex';
      });
    });
  </script>
</body>
</html>
