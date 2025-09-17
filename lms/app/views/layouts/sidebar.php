 <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="form-inline me-auto">
        <ul class="navbar-nav navbar-left">
          <li>
            <a href="<?=BASE_URL?>dashboard" 
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="<?=BASE_URL?>/assets/img/logo-1.png" class="">
            </a>
          </li>
        </ul>
          
        </div>
        <ul class="navbar-nav me-3">
            
            
           
          </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html">
            <?php
            <?php
              $vendor = $_SESSION['vendor'] ?? [];
              $logoName = $vendor['business_name']
                ?? ($vendor['bussines_name'] ?? 'Massage Center');
            ?>
            <span class="logo-name"><?= htmlspecialchars($logoName) ?></span>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
             
            <li class="active">
              <a href="<?=BASE_URL?>dashboard" class="nav-link "><i class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            <li>
              <a href="<?=BASE_URL?>laundry/index" class="nav-link"><i class="fas fa-soap"></i><span>Laundry</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Customers</span></a>
              <ul class="dropdown-menu">
                <li class=""><a class="nav-link" href="<?=BASE_URL?>customers/index">New Customers</a></li>
                <li><a class="nav-link" href="<?=BASE_URL?>customers/history">History</a></li>
                <li><a class="nav-link" href="<?=BASE_URL?>customers/customers">All Customers</a></li>
              </ul>
            </li>

              <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-receipt"></i><span>Bills</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="<?=BASE_URL?>bill/list">All Bills</a></li>
                </ul>
              </li>
<li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i
                  class="fas fa-bars"></i></a></li>
            <li>
              <a href="<?= BASE_URL ?>laundry/index" class="nav-link nav-link-lg" title="Laundry">
                <i class="fas fa-soap"></i>
              </a>
            </li>
            <li>
              <a href="<?= BASE_URL ?>bill/list" class="nav-link nav-link-lg" title="Bills">
                <i class="fas fa-receipt"></i>
              </a>
            </li>
            <!-- <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-user-tie"></i><span>Staff</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?//=BASE_URL?>staff/create">Create Staff</a></li>
                <li><a class="nav-link" href="<?//=BASE_URL?>staff/index">Staff Details</a></li>
              </ul>
            </li> -->
          
          <li class="">
              <a href="<?=BASE_URL?>auth/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
          </li>
          
              </ul>
        </aside>
      </div>