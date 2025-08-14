 <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="form-inline me-auto">
          <ul class="navbar-nav navbar-left">
        
          <li class="dropdown"><a href="#" data-bs-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="<?=BASE_URL?>/assets/img/user.png" class="user-img-radious-style">
              <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-left">
              <div class="dropdown-title">Hello <?=$_SESSION['vendor']['email']?></div>
              
              <div class="dropdown-divider"></div>
              <a href="<?=BASE_URL?>auth/logout" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
          
        </div>
        <ul class="navbar-nav me-3">
            
            <li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i
                  class="fas fa-bars"></i></a></li>
           
          </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html">
             
              <span class="logo-name" >Milk Dairy</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
             


            <li class="active">
              <a href="<?=BASE_URL?>dashboard" class="nav-link "><i class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Customers</span></a>
              <ul class="dropdown-menu">
                <li class=""><a class="nav-link" href="<?=BASE_URL?>customer/create">Create Customers</a></li>
                <li><a class="nav-link" href="<?=BASE_URL?>customer/index">Customers Details</a></li>
                
              </ul>
            </li>
          
          <li class="">
              <a href="<?=BASE_URL?>auth/logout" class="nav-link "><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
          </li>
          </ul>
        </aside>
      </div>