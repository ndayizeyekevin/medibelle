
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="icon" href="/cosmeticsstore/images/icon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="/cosmeticsstore/assets/css/bootstrap.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
    <!-- FontAwesome CSS (offline copy) -->
    <link rel="stylesheet" href="/cosmeticsstore/assets/fontawesome/css/all.min.css">

    <!-- Your modified internal CSS files -->
    <link rel="stylesheet" href="/cosmeticsstore/css/style.css">
    <link rel="stylesheet" href="/cosmeticsstore/css/nicepage.css" media="screen">
    <style>
      
    </style>
</head>
<body>
<?php
$config = require_once 'info.php';
$currentPage = basename($_SERVER['PHP_SELF']);
// Use REQUEST_URI for improved active navigation detection.
$currentURI = $_SERVER['REQUEST_URI'];
?>
  <div id='header'>
    <header class="bg-light text-dark header fixed-top mb-5 shadow-lg">
      <nav class="navbar navbar-expand-lg navbar-light text-dark bg-light mx-3 my-3">
        <div class="container-fluid text-dark">
          <!-- Brand Logo -->
          <a class="navbar-brand header-logo d-flex align-items-center" href="/cosmeticsstore/">
            <img src="/cosmeticsstore/images/logo.png" alt="Logo" width="300" height="100" class="me-2">
          </a>

          <!-- Toggler for Mobile View -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          
          <!-- Navigation Links -->
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <!-- Active if current page is index.php and URI does not contain '/contact/' -->
                <a class="nav-link <?= (strpos($currentURI, '/cosmeticsstore/index.php') !== false) ? 'active' : '' ?>" aria-current="page" href="/cosmeticsstore">Shop</a>
              </li>
              <li class="nav-item">
                <!-- Active if URI contains '/contact/' -->
                <a class="nav-link <?= (strpos($currentURI, '/contact/') !== false) ? 'active' : '' ?>" href="/cosmeticsstore/contact">Contact</a>
              </li>
              <li class="nav-item">
                <a class="nav-link me-5 <?= (strpos($currentURI, '/about-us/') !== false) ? 'active' : '' ?>" href="/cosmeticsstore/about-us">About</a>
              </li>
              <li class="nav-item">
                <!-- Styled Login Link with User Icon -->
                <a class="btn btn-outline-success px-3 login-link <?= ($currentPage == '/cosmeticsstore/auth') ? 'active' : '' ?>" href="/cosmeticsstore/auth">
                  <i class="fas fa-user"></i> Staff Login
                </a>
              </li>
            </ul>
          </div>
        </div>
        <?php
        if ($title != 'Terms of Service and Policy of Use') {
          ?>
        <div class="container-fluid d-flex justify-content-start justify-content-lg-end">
          <!-- Shopping Cart Button -->
          <button class="btn btn-primary mt-2 mb-2 <?= (strpos($currentURI, '/cart/') !== false) ? 'active' : '' ?>" id="cartBtn" onclick="window.location.href='/cosmeticsstore/cart/'">
            <i class="fas fa-cart-shopping me-2"></i>Cart <sup class="fs-6 cart-counter">(<span id="cartCount">0</span>)
          </button>
        </div>

        <?php
        }
        ?>
      </nav>
    </header>
  </div>