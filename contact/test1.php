<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Load minified CSS from CDN for faster rendering -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="../images/logo.jpg" type="image/x-icon">
  <!-- Your modified internal CSS files -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/nicepage.css" media="screen">
  <style>
    /* Loader Overlay Styles */
    #loaderOverlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 10000;
      flex-direction: column;
    }
    #loaderOverlay p {
      margin-top: 1rem;
      font-size: 1.2rem;
      color: #054866;
    }
    
    /* Provided Internal CSS Styles */
    .contact-header {
      background: #f8f9fa;
      padding: 30px 0;
      margin-bottom: 30px;
    }
    .header{
        min-height: 30vh;
    }
    .contact-header h1 {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .contact-header p {
      font-size: 3vh;
    }
    .contact-info i {
      color: #ff9800;
      margin-right: 10px;
    }
    /* Header styling moved to CSS for maintainability */
    .header {
      min-height: 30vh;
    }
    /* Custom styles for the login link */
    .navbar-nav .nav-link{
      font-weight: 700;
      color: #fff;
    }
    /* Active nav link styling */
    .navbar-nav .nav-link.active {
      /*background-color: black;*/ /* Active background color */
      color: #3c3;  /* Active text color */
      border-radius: 5px;
    }
    /* Allow navbar items to wrap */
    .custom-navbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
    }
    /* Cart button */
    #cartBtn {
      font-weight: bold;
      padding: 15px;
      width: 150px;
    }
    /* Reorder items on small screens */
    @media (max-width: 768px) {
      /* Brand always first */
      .custom-navbar .navbar-brand {
        order: 1;
        width: 100%;
        text-align: left;
      }
      /* Toggler second */
      .custom-navbar .navbar-toggler {
        order: 2;
        margin: auto;
      }
      /* Collapse third (the navigation links) */
      .custom-navbar .navbar-collapse {
        order: 3;
        width: 100%;
      }
      /* Cart button comes last and spans full width */
      #cartBtn {
        order: 4;
        width: 30%;
        padding: 8px;
        margin-top: 10px;
        font-weight: normal;
      }
    }
    
    .category-item {
      display: inline-flex;
      align-items: center;
      background: #f8f9fa;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-decoration: none;
      color: #000;
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    .category-item:hover {
      transform: scale(1.05);
    }
    .category-item img {
      width: 50px;
      height: 50px;
      border-radius: 5px;
      margin-right: 10px;
    }
  </style>
</head>
<body>
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
  <!-- Header / Navigation -->
  <?php include '../includes/nav.php';?>  

  <!-- Contact Us Section -->
  <section class="container mb-5">
    <div class="contact-header">
      <h1>Contact Us</h1>
      <p>Have questions or feedback? We're here to help! Reach out to us using the form below, and we'll get back to you as soon as possible.</p>
    </div>
    
    <!-- Display Success or Error Message -->
    <?php
      if (isset($_SESSION['contact_message'])) {
          echo $_SESSION['contact_message'];
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
          <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
      </div>
      <!-- Contact Information -->
      <div class="col-md-4">
        <div class="contact-info">
          <h3>Get in Touch</h3>
          <p><i class="fas fa-map-marker-alt"></i> KK 17 Ave, Kigali, Rwanda</p>
          <p><i class="fas fa-phone"></i> (+250) 781 474 454</p>
          <p><i class="fas fa-envelope"></i> info@sabans-pharmacy.com</p>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>
  
  <!-- Place scripts at the bottom for faster loading -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js" defer></script>
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
