
<!-- Footer at the bottom -->
<footer class="footer mt-5 mb-0 bg-primary text-white text-center py-3 mx-auto w-100">
       <p class=" text-center">&copy; 
          <?php 
            $date = date('Y');
            if($date > 2024){
              echo "2024 - ". $date;
            } else{
              echo date("Y");
            }
          ?> <?= $info['companyName']; ?>. All rights reserved.
        </p>
      
</footer>
          </div>
  <!-- Scroll to Top Button -->
  <div id="scrollToTopBtn" title="Go to top"><i class="fas fa-chevron-up"></i></div>
 
  




<!-- Bootstrap5 Js -->
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
    $(document).ready(function(){
      // Toggle the 'active' class on the sidebar when the toggler is clicked
      $('#navToggler').on('click', function(){
        $('.sidebar').toggleClass('active');
        // Checking if the sidebar is active and update the icon accordingly
        if ($('.sidebar').hasClass('active')) {
          $(this).html('<i class="bi bi-x"></i>');
        } else {
           $(this).html('<i class="bi bi-list"></i>');
        }
      });
    });
</script>