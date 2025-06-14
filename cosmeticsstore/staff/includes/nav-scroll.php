
  <!-- Sidebar Navigation -->
  <nav class="sidebar bg-dark">
    <div class="profile">
      <img src="/cosmeticsstore/images/user.png" alt="Profile Picture">
      <span><!-- Username -->
      <span><?php echo $_SESSION['username']; ?></span></span>
    </div>
    <div class="search">
      <input type="text" placeholder="Search...">
    </div>
    <div class="menu">
      <ul>
        <li>
          <a href="/cosmeticsstore/admin/">
            <i class="bi bi-house"></i> Dashboard
          </a>
        </li>
        <li id="products-menu">
          <a href="/cosmeticsstore/admin/product/">
            <i class="bi bi-box"></i> Products
          </a>
        </li>
        <li>
          <a href="/cosmeticsstore/admin/users">
            <i class="bi bi-people"></i> Users
          </a>
        </li>
        <li>
          <a href="/cosmeticsstore/orders.php">
            <i class="bi bi-cart"></i> Orders
          </a>
        </li>
        <li>
          <a href="/cosmeticsstore/admin/reports/">
            <i class="bi bi-bar-chart"></i> Reports
          </a>
        </li>
        <li>
          <a href="/cosmeticsstore/admin/profile/">
            <i class="bi bi-person"></i> Profile
          </a>
        </li>
        <li>
          <a href="/cosmeticsstore/logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </nav>

