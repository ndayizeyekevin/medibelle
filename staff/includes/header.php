  <!-- Include website info file -->
  <?php $info = require_once $_SERVER['DOCUMENT_ROOT'] .'/cosmeticsstore/includes/info.php'; 
  // echo "<center>". $info['companyName'];
  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $title; ?></title>
  
  <!-- Sidebar -->
  <!-- Fav Icon -->
  <link rel="icon" href="/cosmeticsstore/images/icon.png" type="image/x-icon">
  <!-- CSS -->
  <link rel="stylesheet" href="/cosmeticsstore/staff/css/style.css">
  <!-- Bootstrap CSS -->
  <link href="/cosmeticsstore/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="/cosmeticsstore/assets/bootstrap-icons/bootstrap-icons.css">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="/dams/assetss/css/sweetalert2.min.css">
  <!-- FontAwesome CSS (offline copy) -->
  <link rel="stylesheet" href="/cosmeticsstore/assets/fontawesome/css/all.min.css">
  <style>
    .menu ul {
      list-style-type: none;
      padding: 0;
    }
    p {
      text-align: center !important;
    }    
    
    .menu li {
      margin: 0;
    }
    
    .menu-item {
      display: flex;
      align-items: center;
      /* padding: 10px 15px; */
      color: #ffffff;
      text-decoration: none;
    }
    
    .menu-item:hover, .submenu a:hover {
      background-color: #343a40;
      color: #f8f9fa;
    }
    
    .menu ul a li i {
       /* Add spacing between icon and text */
    }
    
    /* Hide sidebar on small screens and show when toggled */
    @media (max-width: 767px) {
      .sidebar {
        display: none;
        /* color: #f56; */
      }
      .sidebar.active {
        display: block;
        position: fixed;
        top: 0;
        right: 0;
        height: 100%;
        width: 250px;
        z-index: 1050;
        overflow-y: auto;
      }
      .header {
        display: none !important;
      }
      .mobile-header {
        display: flex !important;
        text-align: right !important;
        padding: 10px 10px;
      }


    }

    @media (min-width: 768px) and (max-width: 790px) {
      .header {
        display: none !important;
        /* z-index: 200 !important; */
      }
      .mobile-header {
        display: flex !important;
        text-align: right !important;
        padding: 10px 10px;
      }
      

    }
    .btn.btn-primary:hover {
      background-color:rgb(55, 192, 98) !important; /* Replace with your desired hover color */
      border-color: rgb(75, 213, 119) !important;
    }

    .submenu {
      background-color: #212529; /* slightly lighter than sidebar */
      list-style: none;
      margin: 0;
      padding-left: 1rem;
    }

    .submenu li {
      padding: 0 0 0 1.5rem !important;  

    }

    .submenu a {
      text-decoration: none;
      display: block;
    }

    .menu-item.dropdown-toggle {
      cursor: pointer;
    }

    .menu-item.dropdown-toggle::after {
      content: "▼";
      float: right;
      font-size: 0.6rem;
      margin-left: auto;
      transition: transform 0.2s;
    }

    .menu-item[aria-expanded="true"]::after {
      transform: rotate(180deg);
    }

    .header {
      background: #0d6efd;
    }
    .header {
      position: sticky;
      top: 0;
      z-index: 1030;
    }
    .header button {
      background: inherit;
      color: #fff;
    }
    .footer {
      left: 400px !important;
    }

   /* Loader styles moved here */
    #loader {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(255, 255, 255, 0.9);
      z-index: 9999;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      opacity: 1;
      transition: opacity 2.6s ease, background-color 2.6s ease;
    }

    #loader.hide {
      opacity: 0;
      pointer-events: none;
    }

    #loader img {
      width: 100px;
      animation-duration: 1s;
      animation-name: bounceIn;
      animation-fill-mode: both;
    }

    #loader p {
      margin-top: 1rem;
      font-size: 1.25rem; /* fs-4 */
      color: #0d6efd; /* bootstrap primary blue */
      animation-duration: 1.2s;
      animation-name: fadeInUp;
      animation-fill-mode: both;
    }

    #loader .fa-spinner {
      font-size: 3rem;
      color: #0d6efd;
      margin-top: 1.5rem;
    }

    
    </style>
</head>
<body class="min-h-100">
  <!-- Loader -->
  <!-- Loader with Font Awesome spinner -->
<!-- Loader -->





  <!-- Mobile Navigation Toggler (visible only on small screens) -->
  <div class='bg-dark'>
    <button class="btn btn-primary d-md-none p-3 m-2" id="navToggler">
      <i class="bi bi-list"></i>
    </button>
    <h5 class="text-light d-none mobile-header"><?= $title; ?></h5>
  </div>  
  
  <nav class="sidebar bg-dark">
    <div class="profile">
      <img src="/cosmeticsstore/images/user.png" alt="Profile Picture">
      <span><?php echo ucwords($_SESSION['username']); ?></span>
    </div>
    <div class="search">
      <input type="text" placeholder="Search...">
    </div>
    <div class="menu mt-5">
      <ul>
        <a href="/cosmeticsstore/staff/" class="menu-item">
          <li><i class="bi bi-house"></i> Dashboard</li>
        </a>

        <!-- Products with Submenu -->
        <li class="menu-item dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#submenuProducts" aria-expanded="false">
          <i class="bi bi-box me-2"></i> Products
        </li>
        <ul class="collapse submenu" id="submenuProducts">
          <li><a href="/cosmeticsstore/staff/product/add" class="text-white">Add</a></li>
          <li><a href="/cosmeticsstore/staff/product" class="text-white">View</a></li>
          <li><a href="/cosmeticsstore/staff/product/statistics" class="text-white">Statistics</a></li>
        </ul>

<?php if ($_SESSION['username'] == 'admin'){
  ?>
        <!-- Users with Submenu -->
        <li class="menu-item dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#submenuUsers" aria-expanded="false">
          <i class="bi bi-people me-2"></i> Users
        </li>
        <ul class="collapse submenu" id="submenuUsers">
          <li><a href="/cosmeticsstore/staff/users/add" class="text-white">Add</a></li>
          <li><a href="/cosmeticsstore/staff/users" class="text-white">View</a></li>
        </ul>
<?php
}
?>

        <!-- Orders with Submenu -->
        <li class="menu-item dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#submenuOrders" aria-expanded="false">
          <i class="bi bi-cart me-2"></i> Orders
        </li>
        <ul class="collapse submenu" id="submenuOrders">
          <li><a href="/cosmeticsstore/staff/orders" class="text-white">Regular Orders</a></li>
          <li><a href="/cosmeticsstore/staff/orders/prescription" class="text-white">Prescription Orders</a></li>
          <li><a href="/cosmeticsstore/staff/orders-list" class="text-white">View Lists</a></li>
        </ul>

        <a href="/cosmeticsstore/staff/reports/" class="menu-item">
          <li><i class="bi bi-bar-chart"></i> Reports</li>
        </a>
        <a href="/cosmeticsstore/staff/profile/" class="menu-item">
          <li><i class="bi bi-person"></i> Profile</li>
        </a>
        <a href="/cosmeticsstore/logout.php" class="menu-item">
          <li><i class="bi bi-box-arrow-right"></i> Logout</li>
        </a>
      </ul>

    </div>
  </nav>
  
  
  <!-- Your page content goes here -->
  
  
  
  
  
  
  
  
  <div class="content header text-white d-flex justify-content-between align-items-center px-4 mb-2 shadow-lg me-0" style="height: 10vh !important;">
    <h5 class="mb-0"><?= $title; ?></h5>
 
    <!-- Profile Icon Dropdown -->
    <div class="dropdown mx-3">
      <button class="btn dropdown-toggle d-none d-md-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="/cosmeticsstore/images/user-icon.png" alt="Profile" width="32" height="32" class="rounded-circle me-2">
        <span><?= ucwords($_SESSION['username']); ?></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="/cosmeticsstore/staff">🏠 Home</a></li>
        <li><a class="dropdown-item" href="/cosmeticsstore/staff/profile">👤 Profile</a></li>
        <li><a class="dropdown-item text-danger" href="/cosmeticsstore/logout.php"><li><i class="fas fa-sign-out-alt mx-1"></i>Logout</a></li>
      </ul>
    </div>
  </div>

  <!-- Main Content Area with min-height 100vh -->
  <div class="content d-flex flex-column min-vh-100">
    <!-- Header with dark background and 20vh height -->
