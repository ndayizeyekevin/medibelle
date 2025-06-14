
  <!-- Header / Navigation -->
  <?php 
    $title   = 'Shopping Cart';
    include    '../includes/header.php'; 
    $email   = $config['email'];
    $phone   = $config['phone'];
    $number  = $config['number'];
    $address = $config['location'];

  ?>  
  <!-- Loader Overlay -->
  <div id="loaderOverlay">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Sending...</span>
    </div>
    <p>Sending your message...</p>
  </div>

  <!-- About Section -->
  <div class="container main-content">
    <section class="about-section">
        <div class="mt-5">
        <!-- Who We Are -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
            <h2>Who We Are</h2>
            <p>At Sabans Pharmacy, we deliver transparent, reliable, and cutting-edge pharmaceutical services. Located in the heart of Kigali, Rwanda, our online platform is designed with you in mind—combining modern technology with genuine care for our community's well-being.</p>
            </div>
            <div class="col-md-6">
            <img src="../images/medicines.webp" alt="Sabans Pharmacy" class="img-fluid rounded">
            </div>
        </div>

        <!-- Our Mission -->
        <div class="row align-items-center mb-5" id="mission">
            <div class="col-md-6 order-md-2">
            <h2>Our Mission</h2>
            <p>We are committed to supplying quality healthcare products accessible and affordable. Our mission is to provide an online pharmacy experience that is both convenient and trustworthy. We embrace a forward-thinking approach and are constantly evolving to better serve you.</p>
            </div>
            <div class="col-md-6 order-md-1">
            <img src="../images/skin-care.webp" alt="Our Mission" class="img-fluid rounded">
            </div>
        </div>

        <!-- Our Values -->
        <div class="row mb-5">
            <div class="col-12">
            <h2>Our Values</h2>
            <p>We believe in honesty, innovation, and a deep commitment to health and well-being. We tell it like it is—without sugar-coating—and strive to be transparent in everything we do. At Sabans Pharmacy, you can expect forward-thinking service that puts you first.</p>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="row">
            
            
            <div class="col-12">
                <h2>Contact Us</h2>
                <p>If you have any questions or need more information, please get in touch. We're here to help and look forward to serving you.</p>
                <div class="contact-info">
                    <h3 class="text-success">Contacts</h3>
                    <p>
                        <i class="fas fa-map-marker-alt"></i> <?php echo $address;?>
                    </p>
                    <p>
                        <i class="fas fa-phone"></i> 
                        <a href="tel:+250786591604"><?php echo $number;?></a>
                    </p>
                    <p>
                        <i class="fas fa-envelope"></i> 
                        <a href="mailto:ndayizeye.kevin@outlook.com">
                            <?php echo $email;?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="mb-5">
        <div class="contact-header">
        <h2>Contact Form</h2>
        <p class="text-dark">Have questions or feedback? We're here to help! Reach out to us using the form below, and we'll get back to you as soon as possible.</p>
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
                    <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo $address; ?>
                    </p>
                    <p>
                        <i class="fas fa-phone me-2"></i> <a href="tel:<?php echo $phone; ?>"><?php echo $number; ?></a>
                    </p>
                    <p>
                        <i class="fas fa-envelope me-2"></i> 
                        <a href="mailto:<?php echo $email; ?>"><?php echo $email;?></a></p>
                </div>
        </div>
        </div>
    </section>
  </div>

  <!-- Footer Section -->
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
