<!-- Include Header -->
<?php
    $title = "Privacy Policy";
    include '../includes/header.php';
?>
  <div class="container">
    <div class="container mb-4">
      <h1 class="mb-4">Privacy Policy</h1>
      <p><em>Last Updated: </em><strong>June 2<sup>nd</sup>, 2025</strong></p>
      
      <div class="section">
        <h2>1. Introduction</h2>
        <p>Welcome to <?= $config['companyName']; ?>. We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services.</p>
      </div>

      <div class="section">
        <h2>2. Information We Collect</h2>
        <p>We may collect the following types of information:</p>
        <ul>
          <li><strong>Personal Information:</strong> Name, email address, phone number, shipping address, and payment details when you place an order.</li>
          <li><strong>Device Information:</strong> IP address, browser type, operating system, and other technical details when you access our website.</li>
          <li><strong>Usage Data:</strong> Pages visited, time spent on site, and other analytics to improve our services.</li>
        </ul>
      </div>

      <div class="section">
        <h2>3. How We Use Your Information</h2>
        <p>We use the collected information for the following purposes:</p>
        <ul>
          <li>To process and fulfill your orders</li>
          <li>To communicate with you about your orders and inquiries</li>
          <li>To improve our website and services</li>
          <li>To prevent fraud and enhance security</li>
          <li>To comply with legal obligations</li>
        </ul>
      </div>

      <div class="section">
        <h2>4. Information Sharing and Disclosure</h2>
        <p>We do not sell your personal information. We may share your information with:</p>
        <ul>
          <li>Service providers (e.g., payment processors, shipping companies) necessary to fulfill your orders</li>
          <li>Legal authorities when required by law or to protect our rights</li>
          <li>Business partners in case of mergers or acquisitions</li>
        </ul>
      </div>

      <div class="section">
        <h2>5. Data Security</h2>
        <p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
      </div>

      <div class="section">
        <h2>6. Cookies and Tracking Technologies</h2>
        <p>We use cookies and similar technologies to enhance your browsing experience, analyze site usage, and improve our services. You can manage cookie preferences through your browser settings.</p>
      </div>

      <div class="section">
        <h2>7. Third-Party Links</h2>
        <p>Our website may contain links to third-party sites. We are not responsible for the privacy practices of these external sites. Please review their privacy policies separately.</p>
      </div>

      <div class="section">
        <h2>8. Children's Privacy</h2>
        <p>Our services are not directed to individuals under 18. We do not knowingly collect personal information from children. If we become aware of such collection, we will take steps to delete the information.</p>
      </div>

      <div class="section">
        <h2>9. Your Rights and Choices</h2>
        <p>Depending on your location, you may have certain rights regarding your personal information, including:</p>
        <ul>
          <li>Accessing or updating your information</li>
          <li>Requesting deletion of your data</li>
          <li>Opting out of marketing communications</li>
          <li>Restricting or objecting to processing</li>
        </ul>
        <p>To exercise these rights, please contact us using the information below.</p>
      </div>

      <div class="section">
        <h2>10. Data Retention</h2>
        <p>We retain your personal information only as long as necessary to fulfill the purposes outlined in this policy, unless a longer retention period is required by law.</p>
      </div>

      <div class="section">
        <h2>11. International Data Transfers</h2>
        <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your data.</p>
      </div>

      <div class="section">
        <h2>12. Changes to This Policy</h2>
        <p>We may update this Privacy Policy periodically. The updated version will be posted on our website with a new effective date. Your continued use of our services constitutes acceptance of the revised policy.</p>
      </div>

      <div class="section">
        <h2>13. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy or our data practices, please contact us at:</p>
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
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>

</body>
</html>