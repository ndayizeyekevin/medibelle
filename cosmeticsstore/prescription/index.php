<?php
$title = "Upload Prescription";
    include "../includes/header.php";
?>
        <style>
            .upload-container {
                max-width: 600px;
                margin: 50px auto;
                padding: 30px;
                background-color: #f8f9fa;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                /* text-align: center; */
            }
    
            .upload-container h1 {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }
    
            .form-label {
                font-weight: bold;
            }
    
            .upload-btn {
                margin-top: 20px;
            }
    
            .back-link {
                margin-top: 15px;
                display: inline-block;
            }
        </style>

    <div class="container">
  <div class="upload-container shadow-lg">
    <h1>Upload Your Prescription</h1>
    <form id="uploadForm" action="/cosmeticsstore/includes/upload-handler.php" method="POST" enctype="multipart/form-data">
      
      <!-- Full Name -->
      <div class="mb-3">
        <label for="fullName" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" required>
      </div>

      <!-- Email (new) -->
      <div class="mb-3">
        <label for="emailAddress" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="emailAddress" name="email" placeholder="you@example.com" required>
      </div>

      <!-- Phone Number -->
      <div class="mb-3">
        <label for="phoneNumber" class="form-label">Phone Number</label>
        <input type="tel" 
               class="form-control" 
               id="phoneNumber" 
               name="phoneNumber" 
               placeholder="0788123456"
               minlength="10" maxlength="10"
               pattern="^(078|079|072|073)\d{7}$"
               inputmode="numeric"
               oninput="this.value = this.value.replace(/[^0-9]/g, '');"
               title="Enter a 10-digit number starting with 078, 079, 072, or 073"
               required>
      </div>

      <!-- Address -->
      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" required>
      </div>

      <!-- File -->
      <div class="mb-3">
        <label for="prescriptionFile" class="form-label">Select your prescription file</label>
        <input type="file" class="form-control" id="prescriptionFile" name="prescription" accept=".jpg,.jpeg,.png,.pdf" required>
      </div>

      <!-- Submit Button -->
      <button id="uploadBtn" type="submit" class="btn btn-success upload-btn">
        <i id="uploadIcon" class="fas fa-upload me-2"></i>
        Upload Prescription
      </button>
    </form>

    <a href="../" class=" text-success">← Back to Home</a>
  </div>
</div>

<?php include "../includes/footer.php"; ?>

<script>
  
document.addEventListener('DOMContentLoaded', () => {
  const form        = document.getElementById('uploadForm');
  const fullName    = document.getElementById('fullName');
  const emailField  = document.getElementById('emailAddress');
  const phone       = document.getElementById('phoneNumber');
  const address     = document.getElementById('address');
  const fileIn      = document.getElementById('prescriptionFile');
  const uploadBtn   = document.getElementById('uploadBtn');

  form.addEventListener('submit', e => {
    // 1) Name
    if (!fullName.value.trim()) {
      e.preventDefault();
      Swal.fire('Name Required', 'Please enter your full name.', 'warning');
      fullName.focus();
      return;
    }

    // 2) Email
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRe.test(emailField.value)) {
      e.preventDefault();
      Swal.fire('Invalid Email', 'Please enter a valid email address.', 'error');
      emailField.focus();
      return;
    }

    // 3) Phone
    const phoneRe = /^(078|079|072|073)\d{7}$/;
    if (!phoneRe.test(phone.value)) {
      e.preventDefault();
      Swal.fire('Invalid Phone', 'Enter a 10-digit Rwandan number starting with 078, 079, 072 or 073.', 'error');
      phone.focus();
      return;
    }

    // 4) Address
    if (!address.value.trim()) {
      e.preventDefault();
      Swal.fire('Address Required', 'Please enter your address.', 'warning');
      address.focus();
      return;
    }

    // 5) File presence
    const file = fileIn.files[0];
    if (!file) {
      e.preventDefault();
      Swal.fire('File Required', 'Please select a prescription file.', 'warning');
      fileIn.focus();
      return;
    }

    // 6) File size ≤ 3MB
    const maxSize = 3 * 1024 * 1024;
    if (file.size > maxSize) {
      e.preventDefault();
      Swal.fire('File Too Large', 'Your file must be 3 MB or smaller.', 'error');
      fileIn.focus();
      return;
    }

    // 7) File type
    const allowed = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!allowed.includes(file.type)) {
      e.preventDefault();
      Swal.fire('Invalid File Type', 'Allowed types: JPG, PNG, PDF.', 'error');
      fileIn.focus();
      return;
    }

    // ✅ All good → show spinner & text
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading…';
  });
});

</script>
</body>
</html>

