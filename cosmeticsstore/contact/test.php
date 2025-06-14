<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Scroll to Top Example</title>
  <style>
    /* Style for the scroll-to-top icon */
    #scrollToTop {
      position: fixed;
      bottom: 40px;
      right: 40px;
      width: 50px;
      height: 50px;
      background-color: #054866;
      color: #fff;
      border-radius: 50%;
      text-align: center;
      line-height: 50px;
      font-size: 24px;
      cursor: pointer;
      display: none; /* Hidden by default */
      z-index: 1000;
      transition: opacity 0.3s;
    }
    #scrollToTop:hover {
    background-color:rgb(53, 125, 158);
}
  </style>
</head>
<body>

  <!-- Page content (simulate long page) -->
  <div style="height:2000px; padding: 20px;">
    <h1>Scroll Down to See the "Scroll to Top" Icon</h1>
    <p>Content goes here...</p>
  </div>

  <!-- Scroll to Top Icon -->
  <div id="scrollToTop" title="Go back to top">&#8679;</div> <!-- Unicode arrow up -->

  <script>
    // Show or hide the scroll-to-top icon based on scroll position
    window.addEventListener('scroll', function() {
      const scrollToTop = document.getElementById('scrollToTop');
      if (window.pageYOffset > 300) {
        scrollToTop.style.display = 'block';
      } else {
        scrollToTop.style.display = 'none';
      }
    });

    // Smooth scroll to top when the icon is clicked
    document.getElementById('scrollToTop').addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  </script>
</body>
</html>
